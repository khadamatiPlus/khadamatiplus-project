<?php

namespace Database\Seeders\Lookups;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        DB::table('areas')->truncate(); // Assuming you have an 'areas' table

        $cities = DB::table('cities')->whereIn('name', [
            'Amman', 'Irbid', 'Zarqa', 'Aqaba', 'Madaba', 'Mafraq',
            'Balqa', 'Jerash', 'Ajloun', 'Karak', 'Tafilah', 'Ma\'an'
        ])->get()->pluck('id', 'name');

        if ($cities->isEmpty()) {
            return;
        }

        $areas = [
            'Amman' => [
                ['name' => 'Abdoun', 'name_ar' => 'عبدون'],
                ['name' => 'Jabal Amman', 'name_ar' => 'جبل عمان'],
                ['name' => 'Sweifieh', 'name_ar' => 'الصويفية'],
                ['name' => 'Shmeisani', 'name_ar' => 'الشميساني'],
                ['name' => 'Tla\'a Al-Ali', 'name_ar' => 'طلعة العلي'],
                ['name' => 'Al-Jubaiha', 'name_ar' => 'الجبيهة'],
                ['name' => 'Khalda', 'name_ar' => 'خلدا'],
                ['name' => 'Dahiyat Al-Rasheed', 'name_ar' => 'ضاحية الرشيد'],
                ['name' => 'Al-Rawnaq', 'name_ar' => 'الرونق'],
                ['name' => 'Al-Gardens', 'name_ar' => 'الجاردنز'],
                ['name' => 'Jabal Al-Hussein', 'name_ar' => 'جبل الحسين'],
                ['name' => 'Al-Weibdeh', 'name_ar' => 'الويبدة'],
                ['name' => 'Marj Al-Hamam', 'name_ar' => 'مرج الحمام'],
                ['name' => 'Al-Muqabalayn', 'name_ar' => 'المقابلين'],
                ['name' => 'Sahab', 'name_ar' => 'سحاب'],
                ['name' => 'Bayader Wadi Al-Seer', 'name_ar' => 'بيادر وادي السير'],
                ['name' => 'Sweileh', 'name_ar' => 'صويلح'],
                ['name' => 'Tabarbour', 'name_ar' => 'طبربور'],
                ['name' => 'Marka', 'name_ar' => 'ماركا'],
                ['name' => 'Al-Qweismeh', 'name_ar' => 'القويسمة'],
                ['name' => 'Dabouq', 'name_ar' => 'دابوق'],
                ['name' => 'Um Al-Summaq', 'name_ar' => 'أم السماق'],
                ['name' => 'Al-Yasmeen', 'name_ar' => 'الياسمين'],
                ['name' => 'Al-Rabiah', 'name_ar' => 'الرابية'],
                ['name' => 'Downtown Amman', 'name_ar' => 'وسط البلد عمان'],
            ],
            'Irbid' => [
                ['name' => 'Al-Husn', 'name_ar' => 'الحصن'],
                ['name' => 'Hawara', 'name_ar' => 'حوارة'],
                ['name' => 'University District', 'name_ar' => 'الحي الجامعي'],
                ['name' => 'Downtown Irbid', 'name_ar' => 'وسط البلد إربد'],
            ],
            'Zarqa' => [
                ['name' => 'New Zarqa', 'name_ar' => 'الزرقاء الجديدة'],
                ['name' => 'Old Zarqa', 'name_ar' => 'الزرقاء القديمة'],
                ['name' => 'Russeifa', 'name_ar' => 'الرصيفة'],
                ['name' => 'Jabal Tareq', 'name_ar' => 'جبل طارق'],
            ],
            'Aqaba' => [
                ['name' => 'Al-Sharq', 'name_ar' => 'الشرق'],
                ['name' => 'Al-Gharb', 'name_ar' => 'الغرب'],
                ['name' => 'Port Area', 'name_ar' => 'منطقة الميناء'],
                ['name' => 'Tala Bay', 'name_ar' => 'خليج تالا'],
            ],
            'Madaba' => [
                ['name' => 'Downtown Madaba', 'name_ar' => 'وسط مادبا'],
                ['name' => 'Mount Nebo', 'name_ar' => 'جبل نيبو'],
                ['name' => 'Hanina', 'name_ar' => 'حنينا'],
            ],
            'Mafraq' => [
                ['name' => 'Downtown Mafraq', 'name_ar' => 'وسط المفرق'],
                ['name' => 'Zaatari', 'name_ar' => 'الزعتري'],
                ['name' => 'Ruwaished', 'name_ar' => 'رويشد'],
            ],
            'Balqa' => [
                ['name' => 'Salt', 'name_ar' => 'السلط'],
                ['name' => 'Deir Alla', 'name_ar' => 'دير علا'],
                ['name' => 'Sweimeh', 'name_ar' => 'الصويمة'],
            ],
            'Jerash' => [
                ['name' => 'Downtown Jerash', 'name_ar' => 'وسط جرش'],
                ['name' => 'Souf', 'name_ar' => 'سوف'],
                ['name' => 'Sakib', 'name_ar' => 'ساكب'],
            ],
            'Ajloun' => [
                ['name' => 'Ajloun Castle', 'name_ar' => 'قلعة عجلون'],
                ['name' => 'Ishtafina', 'name_ar' => 'اشتفينا'],
                ['name' => 'Anjara', 'name_ar' => 'عنجرة'],
            ],
            'Karak' => [
                ['name' => 'Karak City', 'name_ar' => 'مدينة الكرك'],
                ['name' => 'Al-Mazar', 'name_ar' => 'المزار'],
                ['name' => 'Ghawr', 'name_ar' => 'الغور'],
            ],
            'Tafilah' => [
                ['name' => 'Tafilah City', 'name_ar' => 'مدينة الطفيلة'],
                ['name' => 'Aima', 'name_ar' => 'عيمة'],
                ['name' => 'Busaira', 'name_ar' => 'بصيرا'],
            ],
            'Ma\'an' => [
                ['name' => 'Ma\'an City', 'name_ar' => 'مدينة معان'],
                ['name' => 'Petra', 'name_ar' => 'البتراء'],
                ['name' => 'Shobak', 'name_ar' => 'الشوبك'],
            ],
        ];

        foreach ($areas as $cityName => $cityAreas) {
            if (!isset($cities[$cityName])) {
                continue;
            }

            foreach ($cityAreas as $area) {
                DB::table('areas')->insert([
                    'name' => $area['name'],
                    'name_ar' => $area['name_ar'],
                    'city_id' => $cities[$cityName],
                    'created_by_id' => 1,
                    'updated_by_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
