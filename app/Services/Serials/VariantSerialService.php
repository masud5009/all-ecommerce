<?php

namespace App\Services\Serials;

use App\Models\ProductVariant;
use App\Models\ProductVariantSerialBatch;
use App\Models\ProductVariantSoldSerial;
use Illuminate\Support\Facades\DB;

class VariantSerialService
{
    public function createBatch(int $variantId, string $batchNo, string $serialStart, string $serialEnd, int $qty): ProductVariantSerialBatch
    {
        if ($qty <= 0) {
            throw new \Exception('Batch qty must be greater than zero.');
        }

        $serialStart = trim($serialStart);
        $serialEnd = trim($serialEnd);

        if (!preg_match('/^\d+$/', $serialStart) || !preg_match('/^\d+$/', $serialEnd)) {
            throw new \Exception('Serial start/end must be numeric.');
        }

        if ($this->compareNumericStrings($serialStart, $serialEnd) > 0) {
            throw new \Exception('Serial end must be greater than or equal to start.');
        }

        return DB::transaction(function () use ($variantId, $batchNo, $serialStart, $serialEnd, $qty) {
            $variant = ProductVariant::lockForUpdate()->findOrFail($variantId);

            $existingBatches = ProductVariantSerialBatch::where('variant_id', $variantId)
                ->lockForUpdate()
                ->get();

            $this->assertRangeHasQty($serialStart, $serialEnd, $qty);

            foreach ($existingBatches as $batch) {
                if ($this->rangesOverlap($serialStart, $serialEnd, $batch->serial_start, $batch->serial_end)) {
                    throw new \Exception('Serial range overlaps with an existing batch.');
                }
            }

            $variant->track_serial = 1;
            $variant->save();

            return ProductVariantSerialBatch::create([
                'variant_id' => $variantId,
                'batch_no' => $batchNo,
                'serial_start' => $serialStart,
                'serial_end' => $serialEnd,
                'qty' => $qty,
                'sold_qty' => 0,
            ]);
        });
    }

    public function allocateSerialsFIFO(int $variantId, int $orderItemId, int $quantity): array
    {
        if ($quantity <= 0) {
            throw new \Exception('Quantity must be greater than zero.');
        }

        return DB::transaction(function () use ($variantId, $orderItemId, $quantity) {
            $batches = ProductVariantSerialBatch::where('variant_id', $variantId)
                ->whereColumn('sold_qty', '<', 'qty')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $remaining = $quantity;
            $allocated = [];

            foreach ($batches as $batch) {
                $available = $batch->qty - $batch->sold_qty;
                if ($available <= 0) {
                    continue;
                }

                $take = min($available, $remaining);
                $width = max(strlen($batch->serial_start), strlen($batch->serial_end));
                $current = $this->addNumericString($batch->serial_start, $batch->sold_qty, $width);

                for ($i = 0; $i < $take; $i++) {
                    $allocated[] = $current;
                    $current = $this->addNumericString($current, 1, $width);
                }

                $batch->sold_qty += $take;
                $batch->save();

                $rows = [];
                foreach (array_slice($allocated, -$take) as $serial) {
                    $rows[] = [
                        'order_item_id' => $orderItemId,
                        'variant_id' => $variantId,
                        'serial' => $serial,
                        'status' => 'sold',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if (count($rows)) {
                    ProductVariantSoldSerial::insert($rows);
                }

                $remaining -= $take;
                if ($remaining <= 0) {
                    break;
                }
            }

            if ($remaining > 0) {
                throw new \Exception('Insufficient serial stock for allocation.');
            }

            return $allocated;
        });
    }

    public function availableStock(int $variantId): int
    {
        return (int) ProductVariantSerialBatch::where('variant_id', $variantId)
            ->selectRaw('COALESCE(SUM(qty - sold_qty), 0) as available')
            ->value('available');
    }

    public function markReturned(int $orderItemId, int $variantId, string $serial): bool
    {
        return DB::transaction(function () use ($orderItemId, $variantId, $serial) {
            $sold = ProductVariantSoldSerial::where('order_item_id', $orderItemId)
                ->where('variant_id', $variantId)
                ->where('serial', $serial)
                ->lockForUpdate()
                ->first();

            if (!$sold) {
                throw new \Exception('Serial not found for this order item.');
            }

            if ($sold->status === 'returned') {
                return true;
            }

            $sold->status = 'returned';
            $sold->returned_at = now();
            $sold->save();

            return true;
        });
    }

    private function assertRangeHasQty(string $start, string $end, int $qty): void
    {
        $width = max(strlen($start), strlen($end));
        $last = $this->addNumericString($start, $qty - 1, $width);
        if ($this->compareNumericStrings($last, $end) > 0) {
            throw new \Exception('Serial range is smaller than qty.');
        }
    }

    private function rangesOverlap(string $startA, string $endA, string $startB, string $endB): bool
    {
        return $this->compareNumericStrings($startA, $endB) <= 0 &&
            $this->compareNumericStrings($endA, $startB) >= 0;
    }

    private function compareNumericStrings(string $a, string $b): int
    {
        $aTrim = ltrim($a, '0');
        $bTrim = ltrim($b, '0');
        $aNorm = $aTrim === '' ? '0' : $aTrim;
        $bNorm = $bTrim === '' ? '0' : $bTrim;

        if (strlen($aNorm) > strlen($bNorm)) return 1;
        if (strlen($aNorm) < strlen($bNorm)) return -1;
        return strcmp($aNorm, $bNorm);
    }

    private function addNumericString(string $base, int $offset, int $width): string
    {
        $offsetStr = (string)max(0, $offset);
        $sum = $this->addNumericStrings($base, $offsetStr);
        return str_pad($sum, $width, '0', STR_PAD_LEFT);
    }

    private function addNumericStrings(string $a, string $b): string
    {
        $a = ltrim($a, '0');
        $b = ltrim($b, '0');
        $a = $a === '' ? '0' : $a;
        $b = $b === '' ? '0' : $b;

        $i = strlen($a) - 1;
        $j = strlen($b) - 1;
        $carry = 0;
        $result = '';

        while ($i >= 0 || $j >= 0 || $carry > 0) {
            $digit = $carry;
            if ($i >= 0) {
                $digit += (int)$a[$i];
                $i--;
            }
            if ($j >= 0) {
                $digit += (int)$b[$j];
                $j--;
            }
            $carry = intdiv($digit, 10);
            $result = (string)($digit % 10) . $result;
        }

        return ltrim($result, '0') === '' ? '0' : ltrim($result, '0');
    }
}
