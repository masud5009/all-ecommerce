<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Table;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }
    /**
     * display table list
     */
    public function index(Request $request)
    {
        $language = $this->requestLang($request->language);
        $user_id = Auth::guard('web')->user()->id;
        $tables = Table::where([['user_id', $user_id], ['language_id', $language->id]])->latest()->get();

        $statuses = [
            'available' => 'Available',
            'occupied' => 'Occupied',
            'reserved' => 'Reserved',
            'cleaning' => 'Cleaning',
            'unavailable' => 'Unavailable',
        ];

        return view('user.tables.index', compact('tables', 'statuses'));
    }

    /**
     * store table data
     */
    public function store(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $request->validate([
            'language_id' => 'required',
            'table_number' => 'required|max:100|unique:tables,table_number,NULL,id,user_id,' . $userId,
            'capacity' => 'required|numeric|min:1',
            'status' => 'required',
            'serial_number' => 'required|numeric|min:1',
        ]);

        Table::create([
            'user_id' => $userId,
            'language_id' => $request->language_id,
            'table_number' => $request->table_number,
            'capacity' => $request->capacity,
            'status' => $request->status,
            'serial_number' => $request->serial_number,
        ]);

        Session::flash('success', __('Created successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * update table data
     */
    public function update(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $table = Table::findOrFail($request->id);

        $request->validate([
            'table_number' => 'required|max:100|unique:tables,table_number,' . $request->id . ',id,user_id,' . $userId,
            'capacity' => 'required|numeric|min:1',
            'status' => 'required',
            'serial_number' => 'required|numeric|min:1',
        ]);

        $table->language_id = $table->language_id;
        $table->table_number = $table->table_number;
        $table->capacity = $table->capacity;
        $table->status = $table->status;
        $table->serial_number = $table->serial_number;
        $table->save();

        Session::flash('success', __('Updated successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * update table status
     */
    public function statusUpdate(Request $request)
    {
        Table::where('id', $request->id)
            ->update(['status' => $request->status]);

        return redirect()->back()->with('success', __('Updated successfully'));
    }

    /**
     * delete single table data
     */
    public function delete(Request $request)
    {
        $tableId = $request->table_id;
        $table = Table::findOrFail($tableId);
        @unlink(public_path('assets/img/tables/' . $table->image));

        $table->delete();
        return redirect()->back()->with('success', __('Deleted successfully'));
    }

    /**
     * bulk delete table list
     */
    public function bulkdelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $table = Table::findOrFail($id);
            @unlink(public_path('assets/img/tables/' . $table->image));
            $table->delete();
        }
        session()->flash('success', __('Deleted successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * display qr code page
     */
    public function qrBuilder($id)
    {
        $table = Table::findOrFail($id);
        $data['table'] = $table;

        return view('user.tables.qr-builder', $data);
    }

    /**
     * show table details
     */
    public function show($id)
    {
        $table = Table::findOrFail($id);
        return view('user.table-details', compact('table'));
    }

    /**
     * generate qr code
     */
    public function generateQr(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'color' => 'required|string',
            'size' => 'required|integer|min:200|max:350',
            'margin' => 'required|integer|min:0|max:5',
            'style' => 'required|in:square,round',
            'eye_style' => 'required|in:square,circle',
            'type' => 'required|in:default,image,text',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_size' => 'nullable|integer|min:1|max:20',
            'image_x' => 'nullable|integer|min:0|max:100',
            'image_y' => 'nullable|integer|min:0|max:100',
            'text' => 'nullable|string|max:50',
            'text_color' => 'nullable|string',
            'text_size' => 'nullable|integer|min:1|max:15',
            'text_x' => 'nullable|integer|min:0|max:100',
            'text_y' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            $table = Table::findOrFail($validated['table_id']);

            // QR code content (URL)
            $qrContent = route('table.show', ['id' => $table->id]);

            // Handle image upload if type is 'image'
            $imageName = null;
            $directory = public_path('assets/img/tables/');
            if ($validated['type'] == 'image' && $request->hasFile('image')) {
                $imageName = $this->qrCodeService->handleImageUpload(
                    $request->file('image'),
                    $directory,
                    $table->id
                );
            }

            // QR code options
            $options = [
                'size' => (int) $validated['size'],
                'color' => (string) $validated['color'],
                'margin' => (int) $validated['margin'],
                'style' => (string) $validated['style'],
                'eye_style' => (string) $validated['eye_style'],
                'background_color' => '#FFFFFF',
            ];

            // if type is image add image options
            if ($validated['type'] == 'image') {
                // use new image if uploaded, otherwise use existing
                $imageToUse = $imageName ?? $table->image;

                if ($imageToUse && file_exists(public_path('assets/img/tables/' . $imageToUse))) {
                    $options['image_path'] = public_path('assets/img/tables/' . $imageToUse);

                    $imageSize = (int) ($request->input('image_size') ?? $table->image_size ?? 30);
                    $options['image_size'] = $imageSize / 100; // Convert to decimal

                    $options['image_x'] = (int) ($request->input('image_x') ?? $table->image_x ?? 50);
                    $options['image_y'] = (int) ($request->input('image_y') ?? $table->image_y ?? 50);
                }
            }

            // if type if text and text is filled add text options
            if ($validated['type'] == 'text' && $request->filled('text')) {
                $options['text'] = (string) $request->input('text');
                $options['text_color'] = (string) ($request->input('text_color') ?? $table->text_color ?? '#000000');
                $options['text_size'] = (int) ($request->input('text_size') ?? $table->text_size ?? 5);
                $options['text_x'] = (int) ($request->input('text_x') ?? $table->text_x ?? 50);
                $options['text_y'] = (int) ($request->input('text_y') ?? $table->text_y ?? 50);
            }

            //generate qr code filename
            $qrFileName = 'qr_' . $table->id . '_' . time() . '.png';
            $directory = public_path('assets/img/tables/');
            $this->qrCodeService->generateQR($qrContent, $qrFileName, $directory, $options);

            // Verify file was created
            $qrFilePath = public_path('assets/img/tables/' . $qrFileName);
            if (!file_exists($qrFilePath)) {
                throw new \Exception('QR code file was not created at: ' . $qrFilePath);
            }

            $fileSize = filesize($qrFilePath);

            if ($fileSize < 100) {
                throw new \Exception('QR code file is too small (corrupted): ' . $fileSize . ' bytes');
            }

            // Prepare update data for database
            $updateData = [
                'color' => $validated['color'],
                'size' => (int) $validated['size'],
                'margin' => (int) $validated['margin'],
                'style' => $validated['style'],
                'eye_style' => $validated['eye_style'],
                'type' => $validated['type'],
                'qr_image' => $qrFileName,
            ];

            // Update image-related fields
            if ($validated['type'] == 'image') {
                $updateData['image_size'] = (int) ($request->input('image_size') ?? $table->image_size ?? 30);
                $updateData['image_x'] = (int) ($request->input('image_x') ?? $table->image_x ?? 50);
                $updateData['image_y'] = (int) ($request->input('image_y') ?? $table->image_y ?? 50);

                if ($imageName) {
                    // Delete old image if exists
                    if ($table->image && file_exists(public_path('assets/img/tables/' . $table->image))) {
                        @unlink(public_path('assets/img/tables/' . $table->image));
                    }
                    $updateData['image'] = $imageName;
                }
            }

            // Update text-related fields
            if ($validated['type'] == 'text') {
                $updateData['text'] = $request->input('text') ?? $table->text ?? '';
                $updateData['text_color'] = $request->input('text_color') ?? $table->text_color ?? '#000000';
                $updateData['text_size'] = (int) ($request->input('text_size') ?? $table->text_size ?? 5);
                $updateData['text_x'] = (int) ($request->input('text_x') ?? $table->text_x ?? 50);
                $updateData['text_y'] = (int) ($request->input('text_y') ?? $table->text_y ?? 50);
            }

            // Delete old QR image if exists
            if ($table->qr_image && file_exists(public_path('assets/img/tables/' . $table->qr_image))) {
                @unlink(public_path('assets/img/tables/' . $table->qr_image));
            }

            // Update table record
            $table->update($updateData);

            // Generate response
            $imageUrl = asset('assets/img/tables/' . $qrFileName);
            $timestamp = time() . rand(1000, 9999);

            return response()->json([
                'success' => true,
                'image_url' => $imageUrl . '?t=' . $timestamp,
                'message' => 'QR code generated successfully',
                'debug' => [
                    'filename' => $qrFileName,
                    'file_exists' => file_exists($qrFilePath),
                    'file_size' => $fileSize,
                    'type' => $validated['type']
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating QR code: ' . $e->getMessage(),
                'error_details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}
