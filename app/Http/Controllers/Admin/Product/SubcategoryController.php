<?php

namespace App\Http\Controllers\Admin\Product;

use Illuminate\Http\Request;
use App\Models\Admin\Language;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
{
    public function index()
    {
        $requestLanguage = request('language');
        $selectedLanguage = Language::where('code', $requestLanguage)->first() ?? app('defaultLang');

        $data['languages'] = app('languages');
        $data['selectedLanguage'] = $selectedLanguage;
        $data['categories'] = ProductCategory::where('language_id', $selectedLanguage->id)
            ->orderBy('serial_number', 'ASC')
            ->get();
        $data['categoryOptionsByLanguage'] = ProductCategory::select('id', 'name', 'language_id')
            ->orderBy('serial_number', 'ASC')
            ->get()
            ->groupBy('language_id')
            ->map(function ($categories) {
                return $categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                    ];
                })->values();
            });
        $data['subcategories'] = ProductSubcategory::with('category:id,name')
            ->where('language_id', $selectedLanguage->id)
            ->orderBy('serial_number', 'ASC')
            ->get();

        return view('admin.product.subcategory.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|max:255|unique:product_subcategories,name',
            'serial_number' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
            'language_id' => 'required|exists:languages,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->after(function ($validator) use ($request) {
            if (!$request->filled('language_id') || !$request->filled('category_id')) {
                return;
            }

            $isValidCategory = ProductCategory::where('id', $request->category_id)
                ->where('language_id', $request->language_id)
                ->exists();

            if (!$isValidCategory) {
                $validator->errors()->add('category_id', __('Please select a category from the selected language.'));
            }
        });

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray(),
            ], 422);
        }

        ProductSubcategory::create([
            'category_id' => $request->category_id,
            'language_id' => $request->language_id,
            'name' => $request->name,
            'slug' => createSlug($request->name),
            'serial_number' => $request->serial_number,
            'status' => $request->status,
        ]);

        session()->flash('success', 'Subcategory create successfully');
        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $subcategory = ProductSubcategory::find($request->id);

        $rules = [
            'id' => 'required|exists:product_subcategories,id',
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|max:255|unique:product_subcategories,name,' . $request->id,
            'serial_number' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->after(function ($validator) use ($request, $subcategory) {
            if (!$subcategory || !$request->filled('category_id')) {
                return;
            }

            $isValidCategory = ProductCategory::where('id', $request->category_id)
                ->where('language_id', $subcategory->language_id)
                ->exists();

            if (!$isValidCategory) {
                $validator->errors()->add('category_id', __('Please select a category from the subcategory language.'));
            }
        });

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray(),
            ], 400);
        }

        $subcategory->update([
            'category_id' => $request->category_id,
            'language_id' => $subcategory->language_id,
            'name' => $request->name,
            'slug' => createSlug($request->name),
            'serial_number' => $request->serial_number,
            'status' => $request->status,
        ]);

        session()->flash('success', 'Subcategory update successfully');
        return response()->json(['status' => 'success'], 200);
    }

    public function delete(Request $request)
    {
        $subcategoryId = $request->subcategory_id;
        $subcategory = ProductSubcategory::findOrFail($subcategoryId);
        $subcategory->delete();

        return redirect()->back()->with('success', 'Subcategory delete successfully');
    }

    public function bulkdelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $subcategory = ProductSubcategory::findOrFail($id);
            $subcategory->delete();
        }

        session()->flash('success', 'Subcategories delete successfully');
        return response()->json(['status' => 'success'], 200);
    }

    public function changeStatus(Request $request)
    {
        ProductSubcategory::where('id', $request->id)->update(['status' => $request->status]);
        return 'success';
    }
}
