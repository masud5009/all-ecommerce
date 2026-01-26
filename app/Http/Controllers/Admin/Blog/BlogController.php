<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ImageUpload;
use App\Http\Requests\Blog\StoreRequest;
use App\Http\Requests\Blog\UpdateRequest;
use App\Models\Admin\Blog;
use App\Models\Admin\Category;
use App\Models\Admin\Language;
use App\Models\BlogContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::where('code', $request->language)->first();
        $information['blogs'] = Blog::join('blog_contents', 'blog_contents.blog_id', 'blogs.id')
            ->join('categories', 'categories.id', 'blog_contents.category_id')
            ->where('blog_contents.language_id', $language->id)
            ->select('blogs.id', 'blogs.image', 'blogs.status', 'blog_contents.title', 'categories.name as categoryName')
            ->get();
        return view('admin.blog.index', $information);
    }

    public function create()
    {
        $languages = app('languages');

        $languages->map(function ($language) {
            $language->categories = Category::where([['status', 1], ['language_id', $language->id]])
                ->orderBy('serial_number', 'desc')
                ->select('id', 'name')
                ->get();
        });

        return view('admin.blog.create', compact('languages'));
    }

    public function store(StoreRequest $request)
    {
        if ($request->hasFile('image')) {
            $image = ImageUpload::store(public_path('assets/img/blog/'), $request->file('image'));
        }

        $blog = new Blog();
        $blog->image = $image;
        $blog->status = $request->status;
        $blog->serial_number = $request->serial_number;
        $blog->save();

        foreach (Language::all() as $language) {
            $code = $language->code;
            if (
                $language->is_default == 1 ||
                $request->filled($code . '_title') ||
                $request->filled($code . '_author') ||
                $request->filled($code . '_category_id') ||
                $request->filled($code . '_text') ||
                $request->filled($code . '_meta_keyword') ||
                $request->filled($code . '_meta_description')
            ) {
                $content = new BlogContent();
                $content->language_id = $language->id;
                $content->blog_id = $blog->id;
                $content->category_id = $request->input($code . '_category_id');
                $content->title = $request->input($code . '_title');
                $content->slug = createSlug($request->input($code . '_title'));
                $content->author = $request->input($code . '_author');
                $content->text = $request->input($code . '_text');
                $content->meta_keyword = json_encode($request->input($code . '_meta_keyword'));
                $content->meta_description = $request->input($code . '_meta_description');
                $content->save();
            }
        }
        Session::flash('success', 'Blog created successfully');
        return 'success';
    }

    public function edit($id, Request $request)
    {
        $blog = Blog::findOrFail($id);

        $languages = app('languages');

        $languages->map(function ($language) use ($blog) {
            $language->content = $blog->content->where('language_id', $language->id)->first();
            $language->is_added = $blog->content->where('language_id', $language->id)->isNotEmpty();
            $language->categories = Category::where([['status', 1], ['language_id', $language->id]])
                ->orderBy('serial_number', 'desc')
                ->select('id', 'name')
                ->get();
        });
        $information['blog'] = $blog;

        return view('admin.blog.edit', $information);
    }

    public function update($id, UpdateRequest $request)
    {
        $blog = Blog::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = ImageUpload::update(public_path('assets/img/blog/'), $request->file('image'), $blog->image);
            @unlink(public_path('assets/img/blog/' . $blog->image));
        } else {
            $image = $blog->image;
        }

        $blog->image = $image;
        $blog->status = $request->status;
        $blog->serial_number = $request->serial_number;
        $blog->save();

        foreach (Language::all() as $language) {
            $code = $language->code;
            $content =  BlogContent::where('blog_id', $blog->id)->where('language_id', $language->id)->first();
            if (empty($content)) {
                $content = new BlogContent();
            }

            if (
                $language->is_default == 1 ||
                $request->filled($code . '_title') ||
                $request->filled($code . '_author') ||
                $request->filled($code . '_category_id') ||
                $request->filled($code . '_text') ||
                $request->filled($code . '_meta_keyword') ||
                $request->filled($code . '_meta_description')
            ) {
                $content->language_id = $language->id;
                $content->blog_id = $blog->id;
                $content->category_id = $request->input($code . '_category_id');
                $content->title = $request->input($code . '_title');
                $content->slug = createSlug($request->input($code . '_title'));
                $content->author = $request->input($code . '_author');
                $content->text = $request->input($code . '_text');
                $content->meta_keyword = json_encode($request->input($code . '_meta_keyword'));
                $content->meta_description = $request->input($code . '_meta_description');
                $content->save();
            }
        }
        Session::flash('success', 'Blog update successfully');
        return 'success';
    }

    public function delete(Request $request)
    {
        $blog_id = $request->blog_id;
        $blog = Blog::findOrFail($blog_id);
        @unlink(public_path('assets/img/blog/') . $blog->image);
        $blog->delete();
        return redirect()->back()->with('success', 'Blog deleted successfully');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $blog = Blog::findOrFail($id);
            @unlink(public_path('assets/img/blog/') . $blog->image);
            $blog->delete();
        }
        session()->flash('success', 'Blog deleted successfully');
        return response()->json(['status' => 'success'], 200);
    }

    public function changeStatus(Request $request)
    {
        Blog::where('id', $request->id)->update(['status' => $request->status]);
        return 'success';
    }
}
