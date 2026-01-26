<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ImageUpload;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Models\Admin\Language;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductContent;
use App\Models\SliderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $language_id = Language::where('code', $request->language)->pluck('id');

        $data['products'] = Product::join('product_contents', 'product_contents.product_id', 'products.id')
            ->join('product_categories', 'product_categories.id', 'product_contents.category_id')
            ->where('product_contents.language_id', $language_id)
            ->where('product_categories.language_id', $language_id)
            ->select('products.id', 'products.thumbnail', 'products.status', 'product_contents.title', 'product_categories.name as categoryName')
            ->orderBy('products.created_at', 'desc')
            ->get();
        return view('admin.product.index', $data);
    }

    public function create(Request $request)
    {
        $default_language = app('defaultLang');
        if ($request->type != 'physical' && $request->type != 'digital') {
            return redirect()->route('admin.product', ['language' => $default_language->code]);
        }
        $languages = app('languages');

        foreach ($languages as $language) {
            $language->categories = ProductCategory::where([['status', 1], ['language_id', $language->id]])
                ->orderBy('serial_number', 'desc')
                ->get();
        }
        $data['languages'] = $languages;

        return view('admin.product.create', $data);
    }

    public function store(StoreRequest $request)
    {

        if ($request->hasFile('thumbnail')) {
            $thumbnail = ImageUpload::store(public_path('assets/img/product/'), $request->file('thumbnail'));
        }


        if ($request->type == 'Digital') {
            $download_file = ImageUpload::store(public_path('assets/img/product/file/'), $request->file('download_file'));
        }

        $product = new Product();
        $product->stock = $request->stock;
        $product->last_restock_qty = $request->stock;
        $product->sku = $request->sku;
        $product->thumbnail = $thumbnail;
        $product->current_price = $request->current_price;
        $product->previous_price = $request->previous_price;
        $product->type = $request->type;
        $product->file_type = $request->file_type;
        $product->download_link = $request->download_link;
        $product->download_file = $download_file ?? NULL;
        $product->status = $request->status;
        $product->save();

        //store slider image
        $sliders = $request->slider_image;
        if ($sliders) {
            $pis = SliderImage::findOrFail($sliders);
            foreach ($pis as $key => $pi) {
                $pi->item_id = $product->id;
                $pi->save();
            }
        }

        $languages = app('languages');
        foreach ($languages as $language) {
            $code = $language->code;
            if (
                $language->is_default == 1 ||
                $request->filled($code . '_title') ||
                $request->filled($code . '_category_id') ||
                $request->filled($code . '_summary') ||
                $request->filled($code . '_description') ||
                $request->filled($code . '_meta_keyword') ||
                $request->filled($code . '_meta_description')
            ) {
                $content = new ProductContent();
                $content->language_id = $language->id;
                $content->product_id = $product->id;
                $content->category_id = $request->input($code . '_category_id');
                $content->title = $request->input($code . '_title');
                $content->slug = createSlug($request->input($code . '_title'));
                $content->summary = $request->input($code . '_summary');
                $content->description = $request->input($code . '_description');
                $content->meta_keyword = json_encode($request->input($code . '_meta_keyword'));
                $content->meta_description = $request->input($code . '_meta_description');
                $content->save();
            }
        }
        Session::flash('success', __('Product created successfully'));
        return 'success';
    }

    public function edit($id)
    {
        $languages = app('languages');

        $product = Product::with('sliderImage')->findOrFail($id);
        $languages->map(function ($language) use ($product) {
            $language->content = $product->content->where('language_id', $language->id)->first();
            $language->categories = ProductCategory::where([['status', 1], ['language_id', $language->id]])
                ->orderBy('serial_number', 'desc')
                ->get();
            $language->is_added = $product->content->where('language_id', $language->id)->isNotEmpty();
        });
        $data['languages'] = $languages;
        $data['product'] = $product;

        return view('admin.product.edit', $data);
    }


    public function update(UpdateRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($request->hasFile('thumbnail')) {
            $thumbnail = ImageUpload::update(public_path('assets/img/product/'), $request->file('thumbnail'), $product->thumbnail);
        }

        if ($product->type == 'Digital') {
            $download_file = ImageUpload::store(public_path('assets/img/product/file/'), $request->file('download_file'));
        } else {
            $download_file = $product->download_file;
        }

        $product->stock = $request->stock;
        $product->last_restock_qty = $request->stock;
        $product->sku = $request->sku;
        $product->thumbnail = $thumbnail ?? $product->thumbnail;
        $product->current_price = $request->current_price;
        $product->previous_price = $request->previous_price;
        $product->type = $request->type;
        $product->file_type = $request->file_type;
        $product->download_link = $request->download_link;
        $product->download_file = $request->file_type == 'upload' ? $download_file : NULL;
        $product->status = $request->status;
        $product->save();

        //store slider image
        $sliders = $request->slider_image;
        if ($sliders) {
            $pis = SliderImage::findOrFail($sliders);
            foreach ($pis as $key => $pi) {
                $pi->item_id = $product->id;
                $pi->save();
            }
        }

        $languages = app('languages');
        foreach ($languages as $language) {
            $code = $language->code;
            $content = ProductContent::where('product_id', $product->id)->where('language_id', $language->id)
                ->first();
            if (empty($content)) {
                $content = new ProductContent();
            }

            if (
                $language->is_default == 1 ||
                $request->filled($code . '_title') ||
                $request->filled($code . '_category_id') ||
                $request->filled($code . '_summary') ||
                $request->filled($code . '_description') ||
                $request->filled($code . '_meta_keyword') ||
                $request->filled($code . '_meta_description')
            ) {
                $content->language_id = $language->id;
                $content->product_id = $product->id;
                $content->category_id = $request->input($code . '_category_id');
                $content->title = $request->input($code . '_title');
                $content->slug = createSlug($request->input($code . '_title'));
                $content->summary = $request->input($code . '_summary');
                $content->description = $request->input($code . '_description');
                $content->meta_keyword = json_encode($request->input($code . '_meta_keyword'));
                $content->meta_description = $request->input($code . '_meta_description');
                $content->save();
            }
        }
        Session::flash('success', __('Product update successfully'));
        return 'success';
    }


    //slider image store
    public function imagesstore(Request $request)
    {
        $directory = public_path('assets/img/product/gallery/');
        $filename =  ImageUpload::sliderStore($request->file('file'), $request->all(), $directory);

        $pi = new SliderImage();
        $pi->item_id = $request->item_id;
        $pi->item_type = 'product';
        $pi->image = $filename;
        $pi->save();
        return response()->json(['status' => 'success', 'file_id' => $pi->id]);
    }
    //slider image delete from create form
    public function imageremove(Request $request)
    {
        $pi = SliderImage::findOrFail($request->value);
        @unlink(public_path('assets/img/product/gallery/') . $pi->image);
        $pi->delete();
        return $pi->id;
    }

    //slider image delete from edit form
    public function dbSliderRemove(Request $request)
    {
        $pi = SliderImage::findOrFail($request->fileid);
        $image_count = SliderImage::where('item_id', $pi->item_id)->get()->count();
        if ($image_count > 1) {
            @unlink(public_path('assets/img/product/gallery/') . $pi->image);
            $pi->delete();
            return 'success';
        } else {
            return 'warning';
        }
    }

    public function changeStatus(Request $request)
    {
        Product::where('id', $request->id)->update(['status' => $request->status]);
        return 'success';
    }
}
