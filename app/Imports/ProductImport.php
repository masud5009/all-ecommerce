<?php

namespace App\Imports;

use App\Services\Import\ProductCsvImportService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $inserted = 0;
    public int $updated = 0;
    public int $skipped = 0;
    public array $errors = [];

    protected ProductCsvImportService $service;

    public function __construct(?ProductCsvImportService $service = null)
    {
        $this->service = $service ?? new ProductCsvImportService();
    }

    public function collection(Collection $rows)
    {
        $result = $this->service->import($rows);
        $this->inserted = $result['inserted'] ?? 0;
        $this->updated = $result['updated'] ?? 0;
        $this->skipped = $result['skipped'] ?? 0;
        $this->errors = $result['errors'] ?? [];
    }
}
