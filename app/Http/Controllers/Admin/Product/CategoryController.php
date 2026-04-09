<?php

namespace App\Http\Controllers\Admin\Product;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $data['languages'] = app('languages');
        $hasUniqueIdColumn = Schema::hasColumn('product_categories', 'unique_id');
        $data['categories'] = ProductCategory::withCount('productContent')
            ->where('language_id', app('defaultLang')->id)
            ->orderBy('serial_number', 'ASC')
            ->get();

        $translationsByUniqueId = collect();
        if ($hasUniqueIdColumn) {
            $uniqueIds = $data['categories']->pluck('unique_id')->filter()->unique()->values();

            if ($uniqueIds->isNotEmpty()) {
                $translationsByUniqueId = ProductCategory::whereIn('unique_id', $uniqueIds)
                    ->get()
                    ->groupBy('unique_id')
                    ->map(function ($items) {
                        return $items->keyBy('language_id');
                    });
            }
        }

        foreach ($data['categories'] as $category) {
            $translations = collect([$category->language_id => $category]);

            if ($hasUniqueIdColumn && filled($category->unique_id)) {
                $translations = $translationsByUniqueId->get($category->unique_id, $translations);
            }

            $translationNames = [];
            $translationIds = [];

            foreach ($data['languages'] as $language) {
                $translation = $translations->get($language->id);
                $translationNames[$language->code] = $translation->name ?? '';
                $translationIds[$language->code] = $translation->id ?? '';
            }

            $category->translation_names = $translationNames;
            $category->translation_ids = $translationIds;
        }

        return view('admin.product.category.index', $data);
    }

    public function store(Request $request)
    {
        $languages = app('languages');
        $defaultLanguage = $languages->firstWhere('is_default', 1) ?? app('defaultLang');
        $hasUniqueIdColumn = Schema::hasColumn('product_categories', 'unique_id');

        $rules = [
            'icon' => 'nullable|string|max:255',
            'serial_number' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
        ];
        $messages = [];

        foreach ($languages as $language) {
            $field = $language->code . '_name';

            $rules[$field] = ($language->id == $defaultLanguage->id ? 'required' : 'nullable') . '|max:255';
            $messages[$field . '.required'] = __('The name field is required for') . ' ' . $language->name . ' ' . __('language.');
            $messages[$field . '.max'] = __('The name field may not be greater than 255 characters for') . ' ' . $language->name . ' ' . __('language.');
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->after(function ($validator) use ($request, $languages, $defaultLanguage) {
            $defaultName = trim((string) $request->input($defaultLanguage->code . '_name'));

            foreach ($languages as $language) {
                $field = $language->code . '_name';
                $name = trim((string) $request->input($field));
                $resolvedName = $name !== '' ? $name : $defaultName;

                if ($resolvedName === '') {
                    continue;
                }

                $exists = ProductCategory::where('language_id', $language->id)
                    ->where('name', $resolvedName)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add(
                        $field,
                        __('The name has already been taken for') . ' ' . $language->name . ' ' . __('language.')
                    );
                }
            }
        });

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        }

        $defaultName = trim((string) $request->input($defaultLanguage->code . '_name'));
        $groupUniqueId = $hasUniqueIdColumn ? uniqid('pc_') : null;
        $hasIconColumn = Schema::hasColumn('product_categories', 'icon');
        $icon = $hasIconColumn && $request->filled('icon')
            ? trim($request->icon)
            : null;

        DB::transaction(function () use ($languages, $request, $defaultName, $groupUniqueId, $hasIconColumn, $hasUniqueIdColumn, $icon) {
            foreach ($languages as $language) {
                $field = $language->code . '_name';
                $name = trim((string) $request->input($field));
                $resolvedName = $name !== '' ? $name : $defaultName;

                $payload = [
                    'language_id' => $language->id,
                    'name' => $resolvedName,
                    'slug' => createSlug($resolvedName),
                    'serial_number' => $request->serial_number,
                    'status' => $request->status
                ];

                if ($hasUniqueIdColumn) {
                    $payload['unique_id'] = $groupUniqueId;
                }

                if ($hasIconColumn) {
                    $payload['icon'] = $icon;
                }

                ProductCategory::create($payload);
            }
        });

        session()->flash('success', __('Category create successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $languages = app('languages');
        $defaultLanguage = $languages->firstWhere('is_default', 1) ?? app('defaultLang');
        $category = ProductCategory::findOrFail($request->id);
        $hasUniqueIdColumn = Schema::hasColumn('product_categories', 'unique_id');
        $hasIconColumn = Schema::hasColumn('product_categories', 'icon');
        $groupUniqueId = $hasUniqueIdColumn ? ($category->unique_id ?: uniqid('pc_')) : null;

        $rules = [
            'icon' => 'nullable|string|max:255',
            'serial_number' => 'required|numeric|min:0',
            'status' => 'required|in:1,0'
        ];
        $messages = [];

        foreach ($languages as $language) {
            $field = $language->code . '_name';

            $rules[$field] = ($language->id == $defaultLanguage->id ? 'required' : 'nullable') . '|max:255';
            $messages[$field . '.required'] = __('The name field is required for') . ' ' . $language->name . ' ' . __('language.');
            $messages[$field . '.max'] = __('The name field may not be greater than 255 characters for') . ' ' . $language->name . ' ' . __('language.');
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->after(function ($validator) use ($request, $languages, $defaultLanguage, $groupUniqueId, $hasUniqueIdColumn) {
            $defaultName = trim((string) $request->input($defaultLanguage->code . '_name'));

            foreach ($languages as $language) {
                $field = $language->code . '_name';
                $name = trim((string) $request->input($field));
                $resolvedName = $name !== '' ? $name : $defaultName;

                if ($resolvedName === '') {
                    continue;
                }

                $query = ProductCategory::where('language_id', $language->id)
                    ->where('name', $resolvedName);

                $translationId = $request->input($language->code . '_translation_id');
                if (filled($translationId)) {
                    $query->where('id', '!=', $translationId);
                } elseif ($hasUniqueIdColumn && filled($groupUniqueId)) {
                    $query->where(function ($subQuery) use ($groupUniqueId) {
                        $subQuery->whereNull('unique_id')
                            ->orWhere('unique_id', '!=', $groupUniqueId);
                    });
                }

                if ($query->exists()) {
                    $validator->errors()->add(
                        $field,
                        __('The name has already been taken for') . ' ' . $language->name . ' ' . __('language.')
                    );
                }
            }
        });

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $defaultName = trim((string) $request->input($defaultLanguage->code . '_name'));
        $icon = $hasIconColumn && $request->filled('icon') ? trim($request->icon) : null;

        DB::transaction(function () use (
            $languages,
            $request,
            $category,
            $defaultName,
            $groupUniqueId,
            $hasIconColumn,
            $hasUniqueIdColumn,
            $icon
        ) {
            if ($hasUniqueIdColumn && blank($category->unique_id)) {
                $category->unique_id = $groupUniqueId;
                $category->save();
            }

            foreach ($languages as $language) {
                $field = $language->code . '_name';
                $translationIdField = $language->code . '_translation_id';
                $name = trim((string) $request->input($field));
                $resolvedName = $name !== '' ? $name : $defaultName;
                $translationId = $request->input($translationIdField);

                $translation = null;
                if (filled($translationId)) {
                    $translation = ProductCategory::where('id', $translationId)
                        ->where('language_id', $language->id)
                        ->first();
                }

                if (!$translation && $hasUniqueIdColumn && filled($groupUniqueId)) {
                    $translation = ProductCategory::where('unique_id', $groupUniqueId)
                        ->where('language_id', $language->id)
                        ->first();
                }

                if (!$translation && $language->id == $category->language_id) {
                    $translation = $category;
                }

                $payload = [
                    'language_id' => $language->id,
                    'name' => $resolvedName,
                    'slug' => createSlug($resolvedName),
                    'serial_number' => $request->serial_number,
                    'status' => $request->status
                ];

                if ($hasUniqueIdColumn) {
                    $payload['unique_id'] = $groupUniqueId;
                }

                if ($hasIconColumn) {
                    $payload['icon'] = $icon;
                }

                if ($translation) {
                    $translation->update($payload);
                } else {
                    ProductCategory::create($payload);
                }
            }
        });

        session()->flash('success', __('Category update successfully'));
        return response()->json(['status' => 'success'], 200);
    }
    public function delete(Request $request)
    {
        $categoryId = $request->category_id;
        $category = ProductCategory::findOrFail($categoryId);

        if (Schema::hasColumn('product_categories', 'unique_id') && filled($category->unique_id)) {
            ProductCategory::where('unique_id', $category->unique_id)->delete();
        } else {
            $category->delete();
        }

        return redirect()->back()->with('success', __('Category delete successfully'));
    }
    public function bulkdelete(Request $request)
    {
        $ids = $request->ids;
        $hasUniqueIdColumn = Schema::hasColumn('product_categories', 'unique_id');
        $uniqueIds = [];

        foreach ($ids as $id) {
            $category = ProductCategory::findOrFail($id);

            if ($hasUniqueIdColumn && filled($category->unique_id)) {
                $uniqueIds[] = $category->unique_id;
            } else {
                $category->delete();
            }
        }

        if (!empty($uniqueIds)) {
            ProductCategory::whereIn('unique_id', array_unique($uniqueIds))->delete();
        }

        session()->flash('success', __('Categories delete successfully'));
        return response()->json(['status' => 'success'], 200);
    }
    public function changeStatus(Request $request)
    {
        $category = ProductCategory::findOrFail($request->id);

        if (Schema::hasColumn('product_categories', 'unique_id') && filled($category->unique_id)) {
            ProductCategory::where('unique_id', $category->unique_id)->update(['status' => $request->status]);
        } else {
            $category->update(['status' => $request->status]);
        }

        return 'success';
    }
}
