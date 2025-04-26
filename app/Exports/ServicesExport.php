<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ServicesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $services;

    public function __construct($services)
    {
        $this->services = $services;
    }

    public function collection()
    {
        return $this->services;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Description',
            'Price',
            'New Price',
            'Duration',
            'Rating',
            'User Ratings Count',
            'Merchant Name',
            'Merchant Mobile',
            'Tags',
        ];
    }

    public function map($service): array
    {
        return [
            $service->id,
            $service->title,
            $service->description,
            $service->price,
            $service->new_price ?? '',
            $service->duration ?? '',
            round($service->reviews->avg('rating'), 2),
            $service->reviews->count(),
            optional($service->merchant)->name,
            optional($service->merchant->profile)->mobile_number,
            $service->tags->pluck('name')->implode(', '),
        ];
    }
}
