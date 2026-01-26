<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExportReport implements FromCollection, WithHeadings, WithMapping
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->orders;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Order Code',
            'Billing Name',
            'Billing Email',
            'Billing Phone',
            'Billing City',
            'Billing Address',
            'Shipping Address',
            'Gateway',
            'Payment Method',
            'Payment Status',
            'Order Status',
            'Cart Total',
            'Discount',
            'Tax',
            'Shipping Charge',
            'Total',
            'Shipping Date',
        ];
    }

    public function map($orders): array
    {
        return [
            $orders->order_number,
            $orders->billing_name ?? __('Guest'),
            $orders->billing_email ?? '-',
            $orders->billing_phone ?? '-',
            $orders->billing_city ?? '-',
            $orders->billing_address ?? '-',
            $orders->shipping_address ?? '-',
            $orders->gateway,
            $orders->payment_method,
            $orders->payment_status,
            $orders->order_status,
            $orders->cart_total,
            $orders->discount_amount,
            $orders->tax,
            $orders->shipping_charge,
            $orders->pay_amount,
            $orders->delivery_date ?? '-',
        ];
    }
}
