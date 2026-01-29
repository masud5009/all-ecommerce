<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_variations') || !Schema::hasTable('product_variation_groups')) {
            return;
        }

        if (DB::table('product_variation_groups')->count() > 0) {
            return;
        }

        $legacyRows = DB::table('product_variations')
            ->orderBy('product_id')
            ->orderBy('indx')
            ->orderBy('language_id')
            ->get();

        if ($legacyRows->isEmpty()) {
            return;
        }

        $groups = [];
        foreach ($legacyRows as $row) {
            $key = $row->product_id . ':' . (int) $row->indx;
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'product_id' => $row->product_id,
                    'sort_order' => (int) $row->indx,
                    'rows' => [],
                ];
            }
            $groups[$key]['rows'][] = $row;
        }

        foreach ($groups as $group) {
            $groupId = DB::table('product_variation_groups')->insertGetId([
                'product_id' => $group['product_id'],
                'sort_order' => $group['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $baseRow = null;
            $maxOptionCount = 0;
            foreach ($group['rows'] as $row) {
                $names = json_decode($row->option_name ?? '[]', true) ?: [];
                $maxOptionCount = max($maxOptionCount, count($names));
                if (!$baseRow && (!empty($row->option_price) || !empty($row->option_stock))) {
                    $baseRow = $row;
                }
            }

            $baseRow = $baseRow ?: $group['rows'][0];
            $basePrices = json_decode($baseRow->option_price ?? '[]', true) ?: [];
            $baseStocks = json_decode($baseRow->option_stock ?? '[]', true) ?: [];
            $maxOptionCount = max($maxOptionCount, count($basePrices), count($baseStocks));

            $optionIds = [];
            for ($i = 0; $i < $maxOptionCount; $i++) {
                $price = isset($basePrices[$i]) ? (float) $basePrices[$i] : 0;
                $stock = isset($baseStocks[$i]) ? (int) $baseStocks[$i] : 0;

                $optionIds[$i] = DB::table('product_variation_options')->insertGetId([
                    'variation_group_id' => $groupId,
                    'position' => $i,
                    'price' => $price,
                    'stock' => $stock,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($group['rows'] as $row) {
                if (!empty($row->variant_name)) {
                    DB::table('product_variation_group_translations')->insertOrIgnore([
                        'variation_group_id' => $groupId,
                        'language_id' => $row->language_id,
                        'name' => $row->variant_name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $optionNames = json_decode($row->option_name ?? '[]', true) ?: [];
                foreach ($optionNames as $index => $name) {
                    if (empty($name) || !isset($optionIds[$index])) {
                        continue;
                    }
                    DB::table('product_variation_option_translations')->insertOrIgnore([
                        'variation_option_id' => $optionIds[$index],
                        'language_id' => $row->language_id,
                        'name' => $name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Data migration rollback intentionally left blank.
    }
};
