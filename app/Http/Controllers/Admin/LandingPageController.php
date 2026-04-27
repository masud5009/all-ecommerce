<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers\ImageUpload;
use App\Models\LandingPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class LandingPageController extends Controller
{
    private string $imageDirectory = 'assets/img/landing_pages/';

    private array $templateMap = [
        'theme_one' => 'landing-page.theme-one',
        'theme_two' => 'landing-page.theme-two',
    ];

    public function index()
    {
        $landingCards = [
            [
                'key' => 'theme_one',
                'title' => __('Theme One'),
                'description' => __('Smart watch style landing page with focused CTA.'),
                'image' => 'assets/admin/noimage.jpg',
                'fields' => [
                    [
                        'name' => 'page_title',
                        'label' => __('Page Title'),
                        'type' => 'text',
                        'placeholder' => __('Enter browser page title'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'brand_name',
                        'label' => __('Brand Name'),
                        'type' => 'text',
                        'placeholder' => __('Enter brand name'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'hero_badge',
                        'label' => __('Hero Badge Text'),
                        'type' => 'text',
                        'placeholder' => __('Enter badge text'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'hero_title',
                        'label' => __('Hero Title'),
                        'type' => 'text',
                        'placeholder' => __('Enter hero title'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'hero_description',
                        'label' => __('Hero Description'),
                        'type' => 'textarea',
                        'placeholder' => __('Enter hero description'),
                        'col' => 12,
                        'rows' => 3,
                    ],
                    [
                        'name' => 'cta_primary_text',
                        'label' => __('Primary Button Text'),
                        'type' => 'text',
                        'placeholder' => __('Enter primary CTA text'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'cta_secondary_text',
                        'label' => __('Secondary Button Text'),
                        'type' => 'text',
                        'placeholder' => __('Enter secondary CTA text'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'rating_text',
                        'label' => __('Rating Text'),
                        'type' => 'text',
                        'placeholder' => __('Example: 4.9/5 from 1,250+ customers'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'product_name',
                        'label' => __('Product Name'),
                        'type' => 'text',
                        'placeholder' => __('Enter product name'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'price_old',
                        'label' => __('Old Price'),
                        'type' => 'text',
                        'placeholder' => __('Enter old price'),
                        'col' => 4,
                    ],
                    [
                        'name' => 'price_now',
                        'label' => __('Current Price'),
                        'type' => 'text',
                        'placeholder' => __('Enter current price'),
                        'col' => 4,
                    ],
                    [
                        'name' => 'save_text',
                        'label' => __('Save Text'),
                        'type' => 'text',
                        'placeholder' => __('Enter discount text'),
                        'col' => 4,
                    ],
                    [
                        'name' => 'hero_image',
                        'label' => __('Hero Product Image'),
                        'type' => 'file',
                        'col' => 12,
                    ],
                ],
            ],
            [
                'key' => 'theme_two',
                'title' => __('Theme Two'),
                'description' => __('Premium supplement style landing page with long-form sections.'),
                'image' => 'assets/admin/noimage.jpg',
                'fields' => [
                    [
                        'name' => 'page_title',
                        'label' => __('Page Title'),
                        'type' => 'text',
                        'placeholder' => __('Enter browser page title'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'brand_name',
                        'label' => __('Brand Name'),
                        'type' => 'text',
                        'placeholder' => __('Enter brand name'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'hero_badge',
                        'label' => __('Hero Badge Text'),
                        'type' => 'text',
                        'placeholder' => __('Enter hero badge text'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'hero_title',
                        'label' => __('Hero Title'),
                        'type' => 'text',
                        'placeholder' => __('Enter hero title'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'hero_description',
                        'label' => __('Hero Description'),
                        'type' => 'textarea',
                        'placeholder' => __('Enter hero description'),
                        'col' => 12,
                        'rows' => 3,
                    ],
                    [
                        'name' => 'product_name',
                        'label' => __('Product Name'),
                        'type' => 'text',
                        'placeholder' => __('Enter product name'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'product_subtitle',
                        'label' => __('Product Subtitle'),
                        'type' => 'text',
                        'placeholder' => __('Enter product subtitle'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'price_now',
                        'label' => __('Current Price'),
                        'type' => 'text',
                        'placeholder' => __('Enter current price'),
                        'col' => 4,
                    ],
                    [
                        'name' => 'price_old',
                        'label' => __('Old Price'),
                        'type' => 'text',
                        'placeholder' => __('Enter old price'),
                        'col' => 4,
                    ],
                    [
                        'name' => 'discount_badge',
                        'label' => __('Discount Badge'),
                        'type' => 'text',
                        'placeholder' => __('Example: 20% OFF'),
                        'col' => 4,
                    ],
                    [
                        'name' => 'primary_cta_text',
                        'label' => __('Primary CTA Text'),
                        'type' => 'text',
                        'placeholder' => __('Enter primary button text'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'secondary_cta_text',
                        'label' => __('Secondary CTA Text'),
                        'type' => 'text',
                        'placeholder' => __('Enter secondary button text'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'product_image',
                        'label' => __('Product Image'),
                        'type' => 'file',
                        'col' => 12,
                    ],
                    [
                        'name' => 'footer_email',
                        'label' => __('Footer Email'),
                        'type' => 'text',
                        'placeholder' => __('Enter contact email'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'footer_phone',
                        'label' => __('Footer Phone'),
                        'type' => 'text',
                        'placeholder' => __('Enter contact number'),
                        'col' => 6,
                    ],
                    [
                        'name' => 'footer_address',
                        'label' => __('Footer Address'),
                        'type' => 'text',
                        'placeholder' => __('Enter address'),
                        'col' => 12,
                    ],
                    [
                        'name' => 'footer_copyright',
                        'label' => __('Footer Copyright Text'),
                        'type' => 'text',
                        'placeholder' => __('Enter copyright line'),
                        'col' => 12,
                    ],
                ],
            ],
        ];

        $generatedPages = LandingPage::latest()->take(20)->get();

        return view('admin.landing_page.index', compact('landingCards', 'generatedPages'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template' => 'required|in:' . implode(',', array_keys($this->templateMap)),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $template = $request->template;
        $payload = (array) $request->input($template, []);
        $payload = $this->storeTemplateImages($request, $template, $payload);

        $pageTitle = !empty($payload['page_title']) ? $payload['page_title'] : ucfirst(str_replace('_', ' ', $template));
        $slug = $this->generateUniqueSlug();

        $landingPage = LandingPage::create([
            'title' => $pageTitle,
            'template' => $template,
            'slug' => $slug,
            'content' => $payload,
        ]);

        return redirect()
            ->back()
            ->with('success', __('Landing page generated successfully'))
            ->with('generated_url', route('frontend.landing_page.show', $landingPage->slug));
    }

    private function storeTemplateImages(Request $request, string $template, array $payload): array
    {
        foreach ($request->allFiles() as $templateKey => $files) {
            if ($templateKey !== $template || !is_array($files)) {
                continue;
            }

            foreach ($files as $field => $file) {
                if (!$file || !$file->isValid()) {
                    continue;
                }

                $directory = public_path($this->imageDirectory);
                $fileName = ImageUpload::store($directory, $file);
                if (!empty($fileName)) {
                    $payload[$field] = $this->imageDirectory . $fileName;
                }
            }
        }

        return $payload;
    }

    private function generateUniqueSlug(): string
    {
        do {
            $slug = 'lp-' . Str::lower(Str::random(10));
        } while (LandingPage::where('slug', $slug)->exists());

        return $slug;
    }
}
