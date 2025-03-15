<?php

namespace Database\Seeders\Lookups;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    public function run()
    {
//        DB::table('cities')->truncate();

        $country = DB::table('countries')->where('id', '=','1')->first();

        if (!$country) {
            return;
        }

        $cities = [
            ['name' => 'Amman', 'name_ar' => 'عمان'],
            ['name' => 'Irbid', 'name_ar' => 'إربد'],
            ['name' => 'Zarqa', 'name_ar' => 'الزرقاء'],
            ['name' => 'Aqaba', 'name_ar' => 'العقبة'],
            ['name' => 'Madaba', 'name_ar' => 'مادبا'],
            ['name' => 'Mafraq', 'name_ar' => 'المفرق'],
            ['name' => 'Balqa', 'name_ar' => 'البلقاء'],
            ['name' => 'Jerash', 'name_ar' => 'جرش'],
            ['name' => 'Ajloun', 'name_ar' => 'عجلون'],
            ['name' => 'Karak', 'name_ar' => 'الكرك'],
            ['name' => 'Tafilah', 'name_ar' => 'الطفيلة'],
            ['name' => 'Ma\'an', 'name_ar' => 'معان'],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'name' => $city['name'],
                'name_ar' => $city['name_ar'],
                'country_id' => $country->id,
                'created_by_id' => 1,
                'updated_by_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
