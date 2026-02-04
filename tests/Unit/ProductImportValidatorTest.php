<?php

namespace Tests\Unit;

use App\Services\Import\ProductImportValidator;
use Tests\TestCase;

class ProductImportValidatorTest extends TestCase
{
    public function test_parse_variant_map_valid()
    {
        $validator = new ProductImportValidator();
        $map = $validator->parseVariantMap('Size=S|Color=Red');

        $this->assertIsArray($map);
        $this->assertSame(['Size' => 'S', 'Color' => 'Red'], $map);
    }

    public function test_parse_variant_map_invalid()
    {
        $validator = new ProductImportValidator();
        $map = $validator->parseVariantMap('SizeS|Color=Red');

        $this->assertNull($map);
    }

    public function test_validate_product_row_requires_fields()
    {
        $validator = new ProductImportValidator();
        $errors = $validator->validateProductRow([
            'row_type' => 'product',
            'product_sku' => '',
            'title' => '',
            'description' => '',
            'type' => 'physical',
        ]);

        $this->assertNotEmpty($errors);
    }

    public function test_validate_variant_row_format()
    {
        $validator = new ProductImportValidator();
        $errors = $validator->validateVariantRow([
            'row_type' => 'variant',
            'group_key' => 'GROUP-1',
            'variant_map' => 'Size=S|Color=Red',
            'variant_sku' => 'SKU-1',
            'variant_price' => '100',
            'variant_stock' => '5',
            'variant_status' => '1',
        ]);

        $this->assertEmpty($errors);
    }

    public function test_validate_variant_row_invalid_map()
    {
        $validator = new ProductImportValidator();
        $errors = $validator->validateVariantRow([
            'row_type' => 'variant',
            'group_key' => 'GROUP-1',
            'variant_map' => 'SizeS|Color=Red',
            'variant_sku' => 'SKU-1',
            'variant_stock' => '5',
            'variant_status' => '1',
        ]);

        $this->assertNotEmpty($errors);
    }
}
