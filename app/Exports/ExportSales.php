<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WIthHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportSales implements FromCollection, WithHeadings, WithMapping
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function headings(): array
    {
        return [
            'Email',
            'Name',
            'Phone',
            'Quantity',
            'Amount',
            'Shipping',
            'Variations',
            'Address',
            'Postal Code',
            'State',
            'Paid At',
        ];
    }

    public function map($order): array
    {
        return [
            $order->email,
            $order->name,
            $order->phone,
            $order->quantity,
            $order->amount/100,
            $order->shipping/100,
            $order->variations,
            $order->address,
            $order->postcode,
            $order->state,
            $order->paid_at,
        ];
    }
    public function collection()
    {
        return $this->orders;
    }

}
