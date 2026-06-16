<?php

namespace Database\Seeders;

use App\Domains\AppService\Models\AppService;
use Illuminate\Database\Seeder;

class AppServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appServices = [
            [
                'name' => 'خدمات تنظيف المنازل',
                'description' => 'خدمات تنظيف منازل احترافية',
                'category_id' => null,
                'status' => 'active',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'خدمات النقل',
                'description' => 'خدمات النقل والانتقال',
                'category_id' => null,
                'status' => 'active',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'خدمات الصيانة',
                'description' => 'خدمات صيانة وإصلاح المنازل',
                'category_id' => null,
                'status' => 'active',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'خدمات الحدائق',
                'description' => 'خدمات تنسيق الحدائق',
                'category_id' => null,
                'status' => 'active',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'خدمات التكييف',
                'description' => 'تركيب وصيانة مكيفات الهواء',
                'category_id' => null,
                'status' => 'active',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'خدمات الكهرباء',
                'description' => 'خدمات التركيب الكهربائي والإصلاح',
                'category_id' => null,
                'status' => 'active',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'خدمات السباكة',
                'description' => 'خدمات السباكة والإصلاح',
                'category_id' => null,
                'status' => 'active',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'خدمات الطلاء',
                'description' => 'خدمات الطلاء والديكور',
                'category_id' => null,
                'status' => 'active',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'خدمات النظافة العامة',
                'description' => 'خدمات النظافة العامة',
                'category_id' => null,
                'status' => 'active',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
            [
                'name' => 'خدمات التركيب',
                'description' => 'خدمات التركيب والتجميع',
                'category_id' => null,
                'status' => 'active',
                'created_by_id' => 1,
                'updated_by_id' => 1,
            ],
        ];

        foreach ($appServices as $appService) {
            AppService::create($appService);
        }
    }
}
