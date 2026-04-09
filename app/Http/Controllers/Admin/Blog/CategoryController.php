<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $languages = app('languages');
        $defaultLanguage = $languages->firstWhere('is_default', 1) ?? app('defaultLang');
        $selectedLanguage = $languages->firstWhere('code', $request->language) ?? $defaultLanguage;
        $categories = Category::where('language_id', $selectedLanguage->id)
            ->orderBy('created_at', 'desc')
            ->select('id', 'name', 'slug', 'serial_number', 'status', 'unique_id', 'language_id')
            ->get();

        $groupedTranslations = collect();
        $uniqueIds = $categories->pluck('unique_id')->filter()->unique()->values();

        if ($uniqueIds->isNotEmpty()) {
            $groupedTranslations = Category::whereIn('unique_id', $uniqueIds)
                ->get()
                ->groupBy('unique_id');
        }

        foreach ($categories as $category) {
            $translations = filled($category->unique_id)
                ? $groupedTranslations->get($category->unique_id, collect([$category]))
                : collect([$category]);

            $category->translation_names = [];
            $category->translation_ids = [];

            foreach ($languages as $language) {
                $translation = $translations->firstWhere('language_id', $language->id);
                $category->translation_names[$language->code] = $translation->name ?? '';
                $category->translation_ids[$language->code] = $translation->id ?? '';
            }
        }

        return view('admin.blog.category.index', [
            'languages' => $languages,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $languages = app('languages');
        $defaultLanguage = $languages->firstWhere('is_default', 1) ?? app('defaultLang');

        try {
            $resolvedNames = $this->validateCategoryRequest($request, $languages, $defaultLanguage);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 400);
        }

        DB::transaction(function () use ($request, $languages, $resolvedNames) {
            $this->syncCategoryTranslations($request, $languages, $resolvedNames, uniqid('bc_'));
        });

        session()->flash('success', __('Category create successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $languages = app('languages');
        $defaultLanguage = $languages->firstWhere('is_default', 1) ?? app('defaultLang');
        $category = Category::findOrFail($request->id);
        $groupUniqueId = $category->unique_id ?: uniqid('bc_');

        try {
            $resolvedNames = $this->validateCategoryRequest($request, $languages, $defaultLanguage, $groupUniqueId);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 400);
        }

        DB::transaction(function () use ($request, $languages, $resolvedNames, $category, $groupUniqueId) {
            if (blank($category->unique_id)) {
                $category->update(['unique_id' => $groupUniqueId]);
            }

            $this->syncCategoryTranslations($request, $languages, $resolvedNames, $groupUniqueId);
        });

        session()->flash('success', __('Category update successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function delete(Request $request)
    {
        $category = Category::findOrFail($request->category_id);

        if (filled($category->unique_id)) {
            Category::where('unique_id', $category->unique_id)->delete();
        } else {
            $category->delete();
        }

        return redirect()->back()->with('success', __('Category delete successfully'));
    }

    public function bulkDelete(Request $request)
    {
        $categories = Category::whereIn('id', $request->ids)->get();
        $groupUniqueIds = $categories->pluck('unique_id')->filter()->unique()->values();

        if ($groupUniqueIds->isNotEmpty()) {
            Category::whereIn('unique_id', $groupUniqueIds)->delete();
        }

        $categories
            ->filter(function (Category $category) {
                return blank($category->unique_id);
            })
            ->each(function (Category $category) {
                $category->delete();
            });

        session()->flash('success', __('Categories delete successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function changeStatus(Request $request)
    {
        $category = Category::findOrFail($request->id);

        if (filled($category->unique_id)) {
            Category::where('unique_id', $category->unique_id)->update(['status' => $request->status]);
        } else {
            $category->update(['status' => $request->status]);
        }

        return 'success';
    }

    private function validateCategoryRequest(Request $request, $languages, $defaultLanguage, ?string $ignoreGroupUniqueId = null): array
    {
        $rules = [
            'serial_number' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
        ];
        $messages = [];
        $defaultField = $defaultLanguage->code . '_name';
        $defaultName = trim((string) $request->input($defaultField));
        $resolvedNames = [];

        foreach ($languages as $language) {
            $field = $language->code . '_name';
            $translatedName = trim((string) $request->input($field));

            $rules[$field] = ($language->id == $defaultLanguage->id ? 'required' : 'nullable') . '|max:255';
            $messages[$field . '.required'] = __('The name field is required for') . ' ' . $language->name . ' ' . __('language.');
            $messages[$field . '.max'] = __('The name field may not be greater than 255 characters for') . ' ' . $language->name . ' ' . __('language.');
            $resolvedNames[$language->id] = $translatedName !== '' ? $translatedName : $defaultName;
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->after(function ($validator) use ($request, $languages, $resolvedNames, $ignoreGroupUniqueId) {
            foreach ($languages as $language) {
                $resolvedName = $resolvedNames[$language->id] ?? '';

                if ($resolvedName === '') {
                    continue;
                }

                $query = Category::where('language_id', $language->id)
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

    private function syncCategoryTranslations(Request $request, $languages, array $resolvedNames, string $groupUniqueId): void
    {
        foreach ($languages as $language) {
            $translationId = $request->input($language->code . '_translation_id');
            $translation = null;

            if (filled($translationId)) {
                $translation = Category::where('id', $translationId)
                    ->where('language_id', $language->id)
                    ->first();
            }

            if (!$translation) {
                $translation = Category::where('unique_id', $groupUniqueId)
                    ->where('language_id', $language->id)
                    ->first();
            }

            $payload = [
                'language_id' => $language->id,
                'unique_id' => $groupUniqueId,
                'name' => $resolvedNames[$language->id],
                'slug' => createSlug($resolvedNames[$language->id]),
                'serial_number' => $request->serial_number,
                'status' => $request->status,
            ];

            if ($translation) {
                $translation->update($payload);
            } else {
                Category::create($payload);
            }
        }
    }
}
