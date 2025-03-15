<?php

namespace Database\Seeders\Lookups;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    public function run()
    {
        // تفريغ الجدول قبل إدخال البيانات
//        DB::table('countries')->truncate();

        DB::table('countries')->insert([
            'code' => 'JO',
            'name' => 'Jordan',
            'name_ar' => 'الأردن',
            'phone_code' => 962,
            'created_by_id' => 1,
            'updated_by_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
