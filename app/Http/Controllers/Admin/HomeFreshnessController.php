<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\Language;
use App\Http\Helpers\ImageUpload;
use App\Models\HomeFreshnessItem;
use App\Models\HomeSectionSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class HomeFreshnessController extends Controller
{
    private string $imageDirectory = 'assets/img/home_section/';

    public function index(Request $request)
    {
        $languages = app('languages');
        $languageCode = $request->language ?? app('defaultLang')->code;
        $language = Language::where('code', $languageCode)->firstOrFail();

        $items = HomeFreshnessItem::where('language_id', $language->id)
            ->orderBy('serial_number', 'asc')
            ->get();

        $section = HomeSectionSetting::where('language_id', $language->id)->first();

        return view('admin.home.freshness.index', [
            'languages' => $languages,
            'selectedLanguage' => $language,
            'items' => $items,
            'section' => $section,
        ]);
    }

    public function updateSection(Request $request)
    {
        $rules = [
            'language_id' => 'required|exists:languages,id',
            'features_title' => 'nullable|string|max:255',
            'features_subtitle' => 'nullable|string|max:255',
            'features_text' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg,avif|max:5120',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray(),
            ], 422);
        }

        $section = HomeSectionSetting::where('language_id', $request->language_id)->first();

        $payload = [
            'features_title' => $request->features_title,
            'features_subtitle' => $request->features_subtitle,
            'features_text' => $request->features_text,
        ];

        $oldImage = $section->features_image ?? null;
        if ($request->hasFile('image')) {
            $directory = public_path($this->imageDirectory);
            $payload['features_image'] = ImageUpload::update($directory, $request->file('image'), $oldImage);
        }

        HomeSectionSetting::updateOrCreate(
            ['language_id' => $request->language_id],
            $payload
        );

        session()->flash('success', __('Freshness section updated successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'language_id' => 'required|exists:languages,id',
            'icon' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'text' => 'nullable|string|max:255',
            'position' => 'required|in:left,right',
            'status' => 'required|in:0,1',
            'serial_number' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray(),
            ], 422);
        }

        HomeFreshnessItem::create([
            'language_id' => $request->language_id,
            'icon' => $request->filled('icon') ? trim($request->icon) : null,
            'title' => $request->title,
            'text' => $request->text,
            'position' => $request->position,
            'status' => $request->status,
            'serial_number' => $request->serial_number,
        ]);

        session()->flash('success', __('Feature item created successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'id' => 'required|exists:home_freshness_items,id',
            'icon' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'text' => 'nullable|string|max:255',
            'position' => 'required|in:left,right',
            'status' => 'required|in:0,1',
            'serial_number' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray(),
            ], 422);
        }

        $item = HomeFreshnessItem::findOrFail($request->id);
        $item->update([
            'language_id' => $item->language_id,
            'icon' => $request->filled('icon') ? trim($request->icon) : null,
            'title' => $request->title,
            'text' => $request->text,
            'position' => $request->position,
            'status' => $request->status,
            'serial_number' => $request->serial_number,
        ]);

        session()->flash('success', __('Feature item updated successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function delete(Request $request)
    {
        $item = HomeFreshnessItem::findOrFail($request->item_id);
        $item->delete();

        return redirect()->back()->with('success', __('Feature item deleted successfully'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids ?? [];

        foreach ($ids as $id) {
            $item = HomeFreshnessItem::find($id);
            if ($item) {
                $item->delete();
            }
        }

        session()->flash('success', __('Feature items deleted successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function changeStatus(Request $request)
    {
        HomeFreshnessItem::where('id', $request->id)->update(['status' => $request->status]);
        return 'success';
    }
}
