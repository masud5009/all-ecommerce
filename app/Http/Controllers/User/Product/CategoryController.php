<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use App\Models\User\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();
        $data['languages'] = $user->languages;

        $data['categories'] = ProductCategory::where('language_id', $user->currentLanguage->id)
            ->orderBy('serial_number', 'ASC')
            ->get();
        return view('user.product.category.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:product_categories,name',
            'serial_number' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
            'language_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        }
        ProductCategory::create([
            'language_id' => $request->language_id,
            'user_id' => Auth::guard('web')->id(),
            'name' => $request->name,
            'slug' => createSlug($request->name),
            'serial_number' => $request->serial_number,
            'status' => $request->status
        ]);
        session()->flash('success', __('Category create successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:product_categories,name,' . $request->id,
            'serial_number' => 'required|numeric|min:0',
            'status' => 'required|in:1,0'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $category = ProductCategory::findOrFail($request->id);
        $category->update(
            [
                'language_id' => $category->language_id,
                'user_id' => Auth::guard('web')->id(),
                'name' => $request->name,
                'slug' => createSlug($request->name),
                'serial_number' => $request->serial_number,
                'status' => $request->status
            ]
        );
        session()->flash('success', __('Category update successfully'));
        return response()->json(['status' => 'success'], 200);
    }
    public function delete(Request $request)
    {
        $categoryId = $request->category_id;
        $category = ProductCategory::findOrFail($categoryId);

        $category->delete();
        return redirect()->back()->with('success', __('Category delete successfully'));
    }
    public function bulkdelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $category = ProductCategory::findOrFail($id);
            $category->delete();
        }
        session()->flash('success', __('Categories delete successfully'));
        return response()->json(['status' => 'success'], 200);
    }
    public function changeStatus(Request $request)
    {
        ProductCategory::where('id', $request->id)->update(['status' => $request->status]);
        return 'success';
    }
}
