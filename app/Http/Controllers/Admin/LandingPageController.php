<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ImageUpload;
use App\Models\LandingPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LandingPageController extends Controller
{
    private string $imageDirectory = 'assets/img/landing_pages/';

    private array $templateMap = [
        'theme_one' => 'landing-page.theme-one',
        'theme_two' => 'landing-page.theme-two',
    ];

    public function index()
    {
        $landingCards = array_map(function ($template) {
            unset($template['sections']);

            return $template;
        }, $this->landingTemplates());

        $generatedPages = LandingPage::latest()->take(20)->get();

        return view('admin.landing_page.index', compact('landingCards', 'generatedPages'));
    }

    public function create(string $template)
    {
        $landingTemplates = $this->landingTemplates();
        $landingTemplate = $landingTemplates[$template] ?? null;

        if (empty($landingTemplate)) {
            abort(404);
        }

        return view('admin.landing_page.create', compact('landingTemplate'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->storeRules($request));

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $template = $request->template;
        $payload = $this->normalizePayload($template, (array) $request->input($template, []));
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
            ->route('admin.landing_page.create', $template)
            ->with('success', __('Landing page generated successfully'))
            ->with('generated_url', route('frontend.landing_page.show', $landingPage->slug));
    }

    private function storeRules(Request $request): array
    {
        $rules = [
            'template' => 'required|in:' . implode(',', array_keys($this->templateMap)),
        ];

        $template = $request->input('template');
        if (!array_key_exists($template, $this->templateMap)) {
            return $rules;
        }

        foreach ($this->fileFieldNames($template) as $fieldName) {
            $rules[$template . '.' . $fieldName] = 'nullable|image|mimes:jpg,jpeg,png,webp,svg,avif|max:4096';
        }

        return $rules;
    }

    private function normalizePayload(string $template, array $payload): array
    {
        $allowedFields = $this->fieldNames($template);
        $fileFields = $this->fileFieldNames($template);
        $normalizedPayload = [];

        foreach ($allowedFields as $fieldName) {
            if (in_array($fieldName, $fileFields, true) || !array_key_exists($fieldName, $payload)) {
                continue;
            }

            $value = $payload[$fieldName];
            if (is_array($value)) {
                continue;
            }

            $normalizedPayload[$fieldName] = is_string($value) ? trim($value) : $value;
        }

        return $normalizedPayload;
    }

    private function storeTemplateImages(Request $request, string $template, array $payload): array
    {
        foreach ($request->allFiles() as $templateKey => $files) {
            if ($templateKey !== $template || !is_array($files)) {
                continue;
            }

            foreach ($files as $field => $file) {
                if (!$file || !$file->isValid() || !in_array($field, $this->fileFieldNames($template), true)) {
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

    private function fieldNames(string $template): array
    {
        return collect($this->landingTemplates()[$template]['sections'] ?? [])
            ->flatMap(fn ($section) => $section['fields'] ?? [])
            ->pluck('name')
            ->unique()
            ->values()
            ->all();
    }

    private function fileFieldNames(string $template): array
    {
        return collect($this->landingTemplates()[$template]['sections'] ?? [])
            ->flatMap(fn ($section) => $section['fields'] ?? [])
            ->filter(fn ($field) => ($field['type'] ?? null) === 'file')
            ->pluck('name')
            ->unique()
            ->values()
            ->all();
    }

    private function landingTemplates(): array
    {
        return [
            'theme_one' => [
                'key' => 'theme_one',
                'title' => __('Theme One'),
                'description' => __('Smart watch style landing page with hero, features, pricing, reviews, order and FAQ sections.'),
                'image' => 'assets/admin/noimage.jpg',
                'sections' => [
                    [
                        'title' => __('Page Setup'),
                        'description' => __('Basic browser, brand and header content.'),
                        'fields' => [
                            $this->field('page_title', __('Page Title'), 'text', 6, __('SmartFit Watch - Product Landing Page')),
                            $this->field('brand_name', __('Brand Name'), 'text', 6, __('SmartFit Watch')),
                            $this->field('header_cta_text', __('Header Button Text'), 'text', 6, __('Order Now')),
                            $this->field('footer_text', __('Footer Text'), 'text', 6, __('2026 SmartFit Watch. All rights reserved.')),
                        ],
                    ],
                    [
                        'title' => __('Hero Section'),
                        'description' => __('Main headline, call to action and product visual.'),
                        'fields' => [
                            $this->field('hero_badge', __('Hero Badge Text'), 'text', 6, __('Limited Offer - Free Delivery')),
                            $this->field('hero_title', __('Hero Title'), 'text', 6, __('Track Your Health & Fitness Every Day')),
                            $this->field('hero_description', __('Hero Description'), 'textarea', 12, __('Premium smart watch with heart-rate monitor, sleep tracking, step counter, long battery life, and stylish design.'), 3),
                            $this->field('cta_primary_text', __('Primary Button Text'), 'text', 6, __('Buy Now')),
                            $this->field('cta_secondary_text', __('Secondary Button Text'), 'text', 6, __('View Features')),
                            $this->field('rating_text', __('Rating Text'), 'text', 6, __('4.9/5 from 1,250+ customers')),
                            $this->field('product_name', __('Product Name'), 'text', 6, __('SmartFit Watch')),
                            $this->field('hero_image', __('Hero Product Image'), 'file', 12),
                        ],
                    ],
                    [
                        'title' => __('Features Section'),
                        'description' => __('Four benefit cards under the hero section.'),
                        'fields' => array_merge(
                            [$this->field('features_title', __('Section Title'), 'text', 12, __('Why Customers Love It'))],
                            $this->repeatCardFields('feature', 4, __('Feature'))
                        ),
                    ],
                    [
                        'title' => __('Daily Life & Price Section'),
                        'description' => __('Lifestyle copy, checklist and pricing card.'),
                        'fields' => [
                            $this->field('lifestyle_title', __('Section Title'), 'text', 6, __('Perfect For Daily Life')),
                            $this->field('lifestyle_description', __('Description'), 'textarea', 12, __('Office, gym, walking, running, or casual use - SmartFit Watch helps you stay connected and active all day.'), 3),
                            $this->field('lifestyle_bullet_1', __('Bullet 1'), 'text', 6, __('Bluetooth calling support')),
                            $this->field('lifestyle_bullet_2', __('Bullet 2'), 'text', 6, __('7 days battery backup')),
                            $this->field('lifestyle_bullet_3', __('Bullet 3'), 'text', 6, __('Water-resistant design')),
                            $this->field('lifestyle_bullet_4', __('Bullet 4'), 'text', 6, __('Compatible with Android & iPhone')),
                            $this->field('price_old', __('Old Price'), 'text', 4, __('Tk 3,990')),
                            $this->field('price_now', __('Current Price'), 'text', 4, __('Tk 2,490')),
                            $this->field('save_text', __('Save Text'), 'text', 4, __('Save Tk 1,500 today')),
                            $this->field('price_cta_text', __('Price Button Text'), 'text', 6, __('Order Now')),
                        ],
                    ],
                    [
                        'title' => __('Reviews Section'),
                        'description' => __('Customer review cards.'),
                        'fields' => array_merge(
                            [$this->field('reviews_title', __('Section Title'), 'text', 12, __('Customer Reviews'))],
                            $this->repeatReviewFields(3)
                        ),
                    ],
                    [
                        'title' => __('Order Section'),
                        'description' => __('Order form heading and button text.'),
                        'fields' => [
                            $this->field('order_title', __('Order Title'), 'text', 6, __('Place Your Order')),
                            $this->field('order_notice', __('Order Notice'), 'text', 6, __('Cash on delivery available all over Bangladesh')),
                            $this->field('order_button_text', __('Order Button Text'), 'text', 6, __('Confirm Order')),
                        ],
                    ],
                    [
                        'title' => __('FAQ Section'),
                        'description' => __('Frequently asked questions.'),
                        'fields' => array_merge(
                            [$this->field('faq_title', __('Section Title'), 'text', 12, __('FAQ'))],
                            $this->repeatFaqFields(3)
                        ),
                    ],
                ],
            ],
            'theme_two' => [
                'key' => 'theme_two',
                'title' => __('Theme Two'),
                'description' => __('Premium supplement style landing page with long-form sections.'),
                'image' => 'assets/admin/noimage.jpg',
                'sections' => [
                    [
                        'title' => __('Page Setup'),
                        'fields' => [
                            $this->field('page_title', __('Page Title'), 'text', 6, __('NutriPure - Premium Supplements')),
                            $this->field('brand_name', __('Brand Name'), 'text', 6, __('NutriPure')),
                        ],
                    ],
                    [
                        'title' => __('Hero Section'),
                        'fields' => [
                            $this->field('hero_badge', __('Hero Badge Text'), 'text', 6, __('Clinically Formulated - 100% Natural')),
                            $this->field('hero_title', __('Hero Title'), 'textarea', 12, __('Fuel Your Best Self Naturally'), 3),
                            $this->field('hero_description', __('Hero Description'), 'textarea', 12, __('Premium plant-based supplements crafted with science-backed ingredients.'), 3),
                            $this->field('primary_cta_text', __('Primary CTA Text'), 'text', 6, __('Shop Now - Save 20%')),
                            $this->field('secondary_cta_text', __('Secondary CTA Text'), 'text', 6, __('See Ingredients')),
                        ],
                    ],
                    [
                        'title' => __('Product & Pricing'),
                        'fields' => [
                            $this->field('product_name', __('Product Name'), 'text', 6, __('Daily Wellness Formula')),
                            $this->field('product_subtitle', __('Product Subtitle'), 'text', 6, __('60 Capsules - 30 Day Supply')),
                            $this->field('price_now', __('Current Price'), 'text', 4, __('Tk 1,490')),
                            $this->field('price_old', __('Old Price'), 'text', 4, __('Tk 1,860')),
                            $this->field('discount_badge', __('Discount Badge'), 'text', 4, __('20% OFF')),
                            $this->field('product_image', __('Product Image'), 'file', 12),
                        ],
                    ],
                    [
                        'title' => __('Footer'),
                        'fields' => [
                            $this->field('footer_email', __('Footer Email'), 'text', 6, __('hello@nutripure.com.bd')),
                            $this->field('footer_phone', __('Footer Phone'), 'text', 6, __('01700-000000')),
                            $this->field('footer_address', __('Footer Address'), 'text', 12, __('Dhaka, Bangladesh')),
                            $this->field('footer_copyright', __('Footer Copyright Text'), 'text', 12, __('2024 NutriPure Bangladesh. All rights reserved.')),
                        ],
                    ],
                ],
            ],
        ];
    }

    private function field(string $name, string $label, string $type = 'text', int $col = 6, ?string $placeholder = null, int $rows = 3): array
    {
        return compact('name', 'label', 'type', 'col', 'placeholder', 'rows');
    }

    private function repeatCardFields(string $prefix, int $count, string $label): array
    {
        $defaults = [
            1 => ['icon' => '&#9989;', 'title' => __('Health Tracking'), 'description' => __('Heart rate, sleep, steps and calories.')],
            2 => ['icon' => '&#128666;', 'title' => __('Fast Delivery'), 'description' => __('Delivery within 24-72 hours.')],
            3 => ['icon' => '&#128737;', 'title' => __('Premium Quality'), 'description' => __('Durable body with modern design.')],
            4 => ['icon' => '&#128257;', 'title' => __('Easy Return'), 'description' => __('7-day replacement support.')],
        ];
        $fields = [];

        for ($index = 1; $index <= $count; $index++) {
            $fields[] = $this->field($prefix . '_' . $index . '_icon', $label . ' ' . $index . ' ' . __('Icon'), 'text', 4, $defaults[$index]['icon'] ?? null);
            $fields[] = $this->field($prefix . '_' . $index . '_title', $label . ' ' . $index . ' ' . __('Title'), 'text', 4, $defaults[$index]['title'] ?? null);
            $fields[] = $this->field($prefix . '_' . $index . '_description', $label . ' ' . $index . ' ' . __('Description'), 'text', 4, $defaults[$index]['description'] ?? null);
        }

        return $fields;
    }

    private function repeatReviewFields(int $count): array
    {
        $defaults = [
            1 => ['text' => __('Battery backup khub valo. Design premium.'), 'author' => __('Verified Customer')],
            2 => ['text' => __('Delivery fast chilo, product exactly same.'), 'author' => __('Verified Customer')],
            3 => ['text' => __('Fitness tracking er jonno perfect.'), 'author' => __('Verified Customer')],
        ];
        $fields = [];

        for ($index = 1; $index <= $count; $index++) {
            $fields[] = $this->field('review_' . $index . '_rating', __('Review') . ' ' . $index . ' ' . __('Rating'), 'text', 4, '&#9733;&#9733;&#9733;&#9733;&#9733;');
            $fields[] = $this->field('review_' . $index . '_text', __('Review') . ' ' . $index . ' ' . __('Text'), 'textarea', 4, $defaults[$index]['text'] ?? null, 2);
            $fields[] = $this->field('review_' . $index . '_author', __('Review') . ' ' . $index . ' ' . __('Author'), 'text', 4, $defaults[$index]['author'] ?? null);
        }

        return $fields;
    }

    private function repeatFaqFields(int $count): array
    {
        $defaults = [
            1 => ['question' => __('Delivery kotodin lage?'), 'answer' => __('Usually 24-72 hours, location er upor depend kore.')],
            2 => ['question' => __('Cash on delivery ache?'), 'answer' => __('Yes, all over Bangladesh cash on delivery available.')],
            3 => ['question' => __('Return policy ache?'), 'answer' => __('Yes, 7-day replacement support available.')],
        ];
        $fields = [];

        for ($index = 1; $index <= $count; $index++) {
            $fields[] = $this->field('faq_' . $index . '_question', __('FAQ') . ' ' . $index . ' ' . __('Question'), 'text', 6, $defaults[$index]['question'] ?? null);
            $fields[] = $this->field('faq_' . $index . '_answer', __('FAQ') . ' ' . $index . ' ' . __('Answer'), 'textarea', 6, $defaults[$index]['answer'] ?? null, 2);
        }

        return $fields;
    }

    private function generateUniqueSlug(): string
    {
        do {
            $slug = 'lp-' . Str::lower(Str::random(10));
        } while (LandingPage::where('slug', $slug)->exists());

        return $slug;
    }
}
