<?php

namespace App\Exports;

use App\Domains\Merchant\Models\Merchant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MerchantsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $merchants;

    public function __construct($merchants)
    {
        $this->merchants = $merchants;
    }

    public function collection()
    {
        return $this->merchants;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Phone Number',
            'Country',
            'City',
            'Approved',
            'Created At'
        ];
    }

    public function map($merchant): array
    {
        return [
            $merchant->name,
            $merchant->profile->mobile_number ?? '',
            $merchant->country->name ?? '',
            $merchant->city->name ?? '',
            $merchant->is_verified ? 'Yes' : 'No',
            $merchant->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
