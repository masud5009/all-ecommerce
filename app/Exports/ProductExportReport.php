<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Product;
use PDF;

class ProductExportReport implements FromCollection, WithHeadings, WithMapping
{
    protected $products;

    public function __construct($products,$type)
    {
        $this->products = $products;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->products;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'SKU',
            'Product Title',
            'Last Restock (Qty)',
            'Current Stock',
            'Current Price',
            'Previous Price',
            'Category',
            'Type',
            'Last Stock In'
        ];
    }

    /**
     * @var Product $product
     * @return array
     */
    public function map($product): array
    {
        return [
            $product->sku,
            $product->title ?? '',
            $product->last_restock_qty,
            $product->stock,
            $product->current_price,
            $product->previous_price,
            $product->categoryName ?? '',
            $product->type,
            $product->created_at,
        ];
    }

    public function styles($sheet)
    {
        // Apply styles here if needed (e.g., background color, font size)
        return [
            // Example: Apply bold to the header row
            1    => ['font' => ['bold' => true]],
        ];
    }
}
