<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductImportTemplate implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'sku',
            'title',
            'category_id',
            'category_name',
            'type',
            'status',
            'stock',
            'current_price',
            'previous_price',
            'summary',
            'description',
            'meta_keyword',
            'meta_description',
            'download_link',
            'group_key',
            'variant_map',
            'variant_sku',
            'variant_price',
            'variant_stock',
            'variant_status',
        ];
    }

    public function collection(): Collection
    {
        return collect([
            [
                'SKU-0001',
                'Sample Product',
                '1',
                '',
                'physical',
                '1',
                '10',
                '99.99',
                '120.00',
                'Short summary',
                'Full description here',
                'tag1, tag2',
                'Meta description',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ],
            [
                'TSHIRT-BASE',
                'T-Shirt Variants',
                '1',
                '',
                'physical',
                '1',
                '',
                '',
                '',
                'Soft cotton tee',
                'Premium cotton t-shirt',
                'tshirt, men',
                'Mens cotton t-shirt',
                '',
                'VAR-GROUP-1',
                'Size=S|Color=Red',
                'TSHIRT-S-RED',
                '499',
                '10',
                '1',
            ],
            [
                'TSHIRT-BASE',
                'T-Shirt Variants',
                '1',
                '',
                'physical',
                '1',
                '',
                '',
                '',
                'Soft cotton tee',
                'Premium cotton t-shirt',
                'tshirt, men',
                'Mens cotton t-shirt',
                '',
                'VAR-GROUP-1',
                'Size=S|Color=White',
                'TSHIRT-S-WHT',
                '499',
                '8',
                '1',
            ],
        ]);
    }
}
