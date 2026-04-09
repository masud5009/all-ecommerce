<?php

namespace App\Http\Controllers\Admin\Product;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $languages = app('languages');
        $defaultLanguage = $this->getLangUsingCode($request->language);

        $categories = ProductCategory::withCount('productContent')
            ->where('language_id', $defaultLanguage->id)
            ->orderBy('serial_number', 'ASC')
            ->get();

        $groupedTranslations = collect();
        $uniqueIds = $categories->pluck('unique_id')->filter()->unique()->values();

        if ($uniqueIds->isNotEmpty()) {
            $groupedTranslations = ProductCategory::whereIn('unique_id', $uniqueIds)
                ->get()
                ->groupBy('unique_id');
        }

        foreach ($categories as $category) {
            $translations = filled($category->unique_id)
                ? $groupedTranslations->get($category->unique_id, collect([$category]))
                : collect([$category]);

            $translationNames = [];
            $translationIds = [];

            foreach ($languages as $language) {
                $translation = $translations->firstWhere('language_id', $language->id);
                $translationNames[$language->code] = $translation->name ?? '';
                $translationIds[$language->code] = $translation->id ?? '';
            }

            $category->translation_names = $translationNames;
            $category->translation_ids = $translationIds;
        }

        return view('admin.product.category.index', [
            'languages' => $languages,
            'categories' => $categories,
            'defaultLanguage' => $defaultLanguage
        ]);
    }

    public function store(Request $request)
    {
        $languages = app('languages');
        $defaultLanguage = $languages->firstWhere('is_default', 1) ?? app('defaultLang');

        try {
            $resolvedNames = $this->validateCategoryRequest($request, $languages, $defaultLanguage);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }

        DB::transaction(function () use ($request, $languages, $defaultLanguage, $resolvedNames) {
            $this->syncCategoryTranslations($request, $languages, $defaultLanguage, $resolvedNames, uniqid('pc_'));
        });

        session()->flash('success', __('Category create successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $languages = app('languages');
        $defaultLanguage = $languages->firstWhere('is_default', 1) ?? app('defaultLang');
        $category = ProductCategory::findOrFail($request->id);
        $groupUniqueId = $category->unique_id ?: uniqid('pc_');

        try {
            $resolvedNames = $this->validateCategoryRequest($request, $languages, $defaultLanguage, $groupUniqueId);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 400);
        }

        DB::transaction(function () use ($request, $languages, $defaultLanguage, $resolvedNames, $category, $groupUniqueId) {
            if (blank($category->unique_id)) {
                $category->update(['unique_id' => $groupUniqueId]);
            }

            $this->syncCategoryTranslations($request, $languages, $defaultLanguage, $resolvedNames, $groupUniqueId);
        });

        session()->flash('success', __('Category update successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function delete(Request $request)
    {
        $category = ProductCategory::findOrFail($request->category_id);

        if ($category->productContent()->exists()) {
            return redirect()->back()->with('warning', __('Please delete the products under this category first.'));
        }

        $category->delete();

        return redirect()->back()->with('success', __('Category delete successfully'));
    }

    public function bulkdelete(Request $request)
    {
        $categories = ProductCategory::withCount('productContent')
            ->whereIn('id', $request->ids)
            ->get();

        $blockedCategories = $categories->filter(function (ProductCategory $category) {
            return (int) $category->product_content_count > 0;
        });

        $categories
            ->reject(function (ProductCategory $category) {
                return (int) $category->product_content_count > 0;
            })
            ->each(function (ProductCategory $category) {
                $category->delete();
            });

        if ($blockedCategories->isNotEmpty()) {
            $blockedNames = $blockedCategories->pluck('name')->take(5)->implode(', ');
            $moreText = $blockedCategories->count() > 5 ? '...' : '';

            session()->flash(
                'warning',
                __('Please delete the products under these categories first:') . ' ' . $blockedNames . $moreText
            );
        }

        if ($categories->count() > $blockedCategories->count()) {
            session()->flash('success', __('Categories delete successfully'));
        }

        return response()->json(['status' => 'success'], 200);
    }

    public function changeStatus(Request $request)
    {
        $category = ProductCategory::findOrFail($request->id);

        if (filled($category->unique_id)) {
            ProductCategory::where('unique_id', $category->unique_id)->update(['status' => $request->status]);
        } else {
            $category->update(['status' => $request->status]);
        }

        return 'success';
    }

    private function validateCategoryRequest(Request $request, $languages, $defaultLanguage, $ignoreGroupUniqueId = null)
    {
        $rules = [
            'icon' => 'nullable|string|max:255',
            'serial_number' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
        ];
        $messages = [];
        $resolvedNames = [];

        foreach ($languages as $language) {
            $field = $language->code . '_name';
            $translatedName = trim((string) $request->input($field));

            $rules[$field] = ($language->id == $defaultLanguage->id ? 'required' : 'nullable') . '|max:255';
            $messages[$field . '.required'] = __('The name field is required for') . ' ' . $language->name . ' ' . __('language.');
            $messages[$field . '.max'] = __('The name field may not be greater than 255 characters for') . ' ' . $language->name . ' ' . __('language.');
            $resolvedNames[$language->id] = $translatedName;
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->after(function ($validator) use ($request, $languages, $resolvedNames, $ignoreGroupUniqueId) {
            foreach ($languages as $language) {
                $resolvedName = $resolvedNames[$language->id] ?? '';

                if ($resolvedName === '') {
                    continue;
                }

                $query = ProductCategory::where('language_id', $language->id)
                    ->where('name', $resolvedName);

                $translationId = $request->input($language->code . '_translation_id');
                if (filled($translationId)) {
                    $query->where('id', '!=', $translationId);
                } elseif ($ignoreGroupUniqueId !== null) {
                    $query->where('unique_id', '!=', $ignoreGroupUniqueId);
                }

                if ($query->exists()) {
                    $validator->errors()->add(
                        $language->code . '_name',
                        __('The name has already been taken for') . ' ' . $language->name . ' ' . __('language.')
                    );
                }
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $resolvedNames;
    }

    private function syncCategoryTranslations(Request $request, $languages, $defaultLanguage, $resolvedNames, $groupUniqueId)
    {
        $icon = $request->filled('icon') ? trim($request->icon) : null;

        foreach ($languages as $language) {
            $translationId = $request->input($language->code . '_translation_id');
            $translation = null;

            if (filled($translationId)) {
                $translation = ProductCategory::where('id', $translationId)
                    ->where('language_id', $language->id)
                    ->first();
            }

            if (!$translation) {
                $translation = ProductCategory::where('unique_id', $groupUniqueId)
                    ->where('language_id', $language->id)
                    ->first();
            }

            $name = trim((string) ($resolvedNames[$language->id] ?? ''));
            $isDefaultLanguage = (int) $language->id === (int) $defaultLanguage->id;

            if (!$isDefaultLanguage && $name === '') {
                continue;
            }

            $payload = [
                'language_id' => $language->id,
                'unique_id' => $groupUniqueId,
                'name' => $name,
                'slug' => createSlug($name),
                'serial_number' => $request->serial_number,
                'status' => $request->status,
                'icon' => $icon,
            ];

            if ($translation) {
                $translation->update($payload);
            } else {
                ProductCategory::create($payload);
            }
        }
    }
}
