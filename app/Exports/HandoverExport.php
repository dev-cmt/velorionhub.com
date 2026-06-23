<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HandoverExport implements FromCollection, WithHeadings, WithMapping
{
    protected $courierId;

    public function __construct($courierId = null)
    {
        $this->courierId = $courierId;
    }

    public function collection()
    {
        return DB::table('handovers')
            ->join('orders', 'handovers.order_id', '=', 'orders.id')
            ->join('couriers', 'orders.courier_id', '=', 'couriers.id')
            ->when($this->courierId, fn($q) => $q->where('orders.courier_id', $this->courierId))
            ->where('handovers.is_temp', 1)
            ->select(
                'orders.invoice_no',
                'couriers.name as courier_name',
                'orders.customer_name',
                'orders.customer_phone',
                'orders.total',
                'handovers.created_at'
            )
            ->orderBy('handovers.created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Invoice No',
            'Courier',
            'Customer Name',
            'Customer Phone',
            'Total',
            'Handover Time',
        ];
    }

    public function map($row): array
    {
        return [
            $row->invoice_no,
            $row->courier_name,
            $row->customer_name,
            $row->customer_phone,
            $row->total,
            $row->created_at,
        ];
    }
}
