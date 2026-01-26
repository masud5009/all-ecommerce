<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $information['languages'] = app('languages');
        $Language_id = Language::where('code', $request->language)->firstOrFail()->id;
        $information['categories'] = Category::where('language_id', $Language_id)
            ->orderBy('created_at', 'desc')
            ->select('id', 'name', 'slug', 'serial_number', 'status')
            ->get();
        return view('admin.blog.category.index', $information);
    }
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'serial_number' => 'required',
            'status' => 'required',
            'language_id' => 'required|exists:languages,id',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }
        Category::create([
            'language_id' => $request->language_id,
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
            'name' => 'required|max:255',
            'serial_number' => 'required',
            'status' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $category = Category::findOrFail($request->id);
        $category->update(
            [
                'language_id' => $category->language_id,
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
        $category = Category::findOrFail($categoryId);

        $category->delete();
        return redirect()->back()->with('success', __('Category delete successfully'));
    }
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $category = Category::findOrFail($id);
            $category->delete();
        }
        session()->flash('success', __('Categories delete successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function changeStatus(Request $request)
    {
        Category::where('id', $request->id)->update(['status' => $request->status]);
        return 'success';
    }
}
