<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ImageUpload;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Models\User\UserLanguage;
use App\Models\User\Product;
use App\Models\User\ProductCategory;
use App\Models\ProductContent;
use App\Models\SliderImage;
use App\Services\Traits\UserMiscellaneousTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    use UserMiscellaneousTrait;
    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();
        $language_id = UserLanguage::where('code', $request->language)
            ->where('user_id', $user->id)
            ->value('id');

        $data['categories'] = ProductCategory::where([
            ['status', 1],
            ['language_id', $language_id],
            ['user_id', Auth::guard('web')->id()]
        ])
            ->orderBy('serial_number', 'desc')
            ->get();


        $data['products'] = Product::join('product_contents', 'product_contents.product_id', 'products.id')
            ->join('product_categories', 'product_categories.id', 'product_contents.category_id')
            ->where('product_contents.language_id', $language_id)
            ->where('product_categories.language_id', $language_id)
            ->where('products.user_id', $user->id)
            ->select('products.id', 'products.thumbnail', 'products.status', 'product_contents.title', 'product_categories.name as categoryName')
            ->orderBy('products.created_at', 'desc')
            ->get();
        return view('user.product.index', $data);
    }

    public function create(Request $request)
    {
        $default_language = $this->getCurrentLang();
        $languages = $this->getUserLanguages();

        if ($request->type != 'physical' && $request->type != 'digital') {
            return redirect()->route('user.product', ['language' => $default_language->code]);
        }

        foreach ($languages as $language) {
            $language->categories = ProductCategory::where([
                ['status', 1],
                ['language_id', $language->id],
                ['user_id', Auth::guard('web')->id()]
            ])
                ->orderBy('serial_number', 'desc')
                ->get();
        }
        $data['languages'] = $languages;

        return view('user.product.create', $data);
    }

    public function store(StoreRequest $request)
    {
        //store thumbnail
        $thumbnail = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = ImageUpload::store(
                public_path('assets/img/product/'),
                $request->file('thumbnail')
            );
        }
        //store download file
        $download_file = null;
        if ($request->type === 'Digital' && $request->file_type === 'upload' && $request->hasFile('download_file')) {
            $download_file = ImageUpload::store(
                public_path('assets/img/product/file/'),
                $request->file('download_file')
            );
        }

        // If it's link-based, use the link
        if ($request->type === 'Digital' && $request->file_type === 'link') {
            $download_file = null;
        }

        $product = new Product();
        $product->user_id = Auth::guard('web')->id();
        $product->stock = $request->stock;
        $product->last_restock_qty = $request->stock;
        $product->sku = $request->sku;
        $product->thumbnail = $thumbnail;
        $product->current_price = $request->current_price;
        $product->previous_price = $request->previous_price;
        $product->type = $request->type;
        $product->file_type = $request->file_type ?? null;
        $product->download_link = $request->download_link ?? null;
        $product->download_file = $download_file;
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

        $languages = $this->getUserLanguages();
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
                $content->user_id = Auth::guard('web')->id();
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
        $languages = $this->getUserLanguages();

        $product = Product::with('sliderImage')->findOrFail($id);
        $languages->map(function ($language) use ($product) {
            $language->content = $product->content->where('language_id', $language->id)->first();
            $language->categories = ProductCategory::where([
                ['status', 1],
                ['language_id', $language->id],
                ['user_id', Auth::guard('web')->id()],
            ])
                ->orderBy('serial_number', 'desc')
                ->get();
            $language->is_added = $product->content->where('language_id', $language->id)->isNotEmpty();
        });
        $data['languages'] = $languages;
        $data['product'] = $product;

        return view('user.product.edit', $data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        $thumbnail = $product->thumbnail;
        $download_file = $product->download_file;

        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            $thumbnail = ImageUpload::update(public_path('assets/img/product/'), $request->file('thumbnail'), $product->thumbnail);
        }

        // Handle digital product files
        if ($product->type == 'Digital') {
            if ($request->file_type == 'upload') {
                if ($request->hasFile('download_file') && $request->file('download_file')->isValid()) {
                    // Delete old file if exists
                    if ($product->download_file) {
                        @unlink(public_path('assets/img/product/file/') . $product->download_file);
                    }
                    $download_file = ImageUpload::store(public_path('assets/img/product/file/'), $request->file('download_file'));
                }
                // If no new file uploaded, keep the existing file ($download_file already contains existing value)
            } else {
                // File type is link, remove file
                if ($product->download_file) {
                    @unlink(public_path('assets/img/product/file/') . $product->download_file);
                }
                $download_file = NULL;
            }
        }

        // Update product
        $product->stock = $request->stock;
        $product->last_restock_qty = $request->stock;
        $product->sku = $request->sku;
        $product->thumbnail = $thumbnail;
        $product->current_price = $request->current_price;
        $product->previous_price = $request->previous_price;
        $product->type = $request->type;
        $product->file_type = $request->file_type;
        $product->download_link = $request->download_link;
        $product->download_file = $download_file;
        $product->status = $request->status;
        $product->save();

        // Store slider image
        $sliders = $request->slider_image;
        if ($sliders) {
            // First, detach existing sliders for this product
            SliderImage::where('item_id', $product->id)->update(['item_id' => null]);

            // Then attach the new ones
            $pis = SliderImage::findOrFail($sliders);
            foreach ($pis as $key => $pi) {
                $pi->item_id = $product->id;
                $pi->save();
            }
        }

        // Update or create product contents
        $languages = app('languages');
        foreach ($languages as $language) {
            $code = $language->code;
            $content = ProductContent::where('product_id', $product->id)
                ->where('language_id', $language->id)
                ->first();

            if (
                $language->is_default == 1 ||
                $request->filled($code . '_title') ||
                $request->filled($code . '_category_id') ||
                $request->filled($code . '_summary') ||
                $request->filled($code . '_description') ||
                $request->filled($code . '_meta_keyword') ||
                $request->filled($code . '_meta_description')
            ) {
                if (empty($content)) {
                    $content = new ProductContent();
                    $content->user_id = Auth::guard('web')->id();
                    $content->product_id = $product->id;
                    $content->language_id = $language->id;
                }

                $content->category_id = $request->input($code . '_category_id');
                $content->title = $request->input($code . '_title');
                $content->slug = createSlug($request->input($code . '_title'));
                $content->summary = $request->input($code . '_summary');
                $content->description = $request->input($code . '_description');
                $content->meta_keyword = json_encode($request->input($code . '_meta_keyword'));
                $content->meta_description = $request->input($code . '_meta_description');
                $content->save();
            } else {
                // Delete content if no data provided and not default language
                if ($content && $language->is_default != 1) {
                    $content->delete();
                }
            }
        }

        Session::flash('success', __('Product updated successfully'));
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


    public function delete(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        //delete download file & thumbnail
        @unlink(public_path('assets/img/product/') . $product->thumbnail);
        @unlink(public_path('assets/img/product/file/', $product->download_file));

        //delete slider images
        $sliders = SliderImage::where('item_id', $product->id)->get();
        foreach ($sliders as $slider) {
            @unlink(public_path('assets/img/product/gallery/') . $slider->image);
            $slider->delete();
        }

        //delete product content
        $product->content()->delete();
        $product->delete();

        return redirect()->back()->with('success', __('Product delete successfully'));
    }
    public function bulkdelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $product = Product::findOrFail($id);

            //delete download file & thumbnail
            @unlink(public_path('assets/img/product/') . $product->thumbnail);
            @unlink(public_path('assets/img/product/file/', $product->download_file));

            //delete slider images
            $sliders = SliderImage::where('item_id', $product->id)->get();
            foreach ($sliders as $slider) {
                @unlink(public_path('assets/img/product/gallery/') . $slider->image);
                $slider->delete();
            }

            //delete product content
            $product->content()->delete();
            $product->delete();
        }
        session()->flash('success', __('Products delete successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function categoryReorder(Request $request)
    {
        foreach ($request->order as $item) {
            ProductCategory::where('id', $item['id'])->update(['serial_number' => $item['order']]);
        }
        return response()->json(['success' => true]);
    }

    public function itemReorder(Request $request)
    {
        foreach ($request->order as $item) {
            Product::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        return response()->json(['success' => true]);
    }
}
