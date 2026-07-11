<?php

namespace Tests\Feature\API;

use App\Domains\AppService\Models\AppService;
use App\Domains\Auth\Models\User;
use App\Domains\Lookups\Models\Category;
use App\Domains\Merchant\Models\Merchant;
use Tests\TestCase;

class MerchantUpdateValidationTest extends TestCase
{
    /** @test */
    public function merchant_update_rejects_app_services_from_different_categories_or_subcategories()
    {
        $categoryOne = Category::query()->create([
            'name' => 'Category One',
            'name_ar' => 'الفئة الأولى',
            'status' => true,
            'parent_id' => null,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        $subCategoryOne = Category::query()->create([
            'name' => 'Sub Category One',
            'name_ar' => 'الفئة الفرعية الأولى',
            'status' => true,
            'parent_id' => $categoryOne->id,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        $categoryTwo = Category::query()->create([
            'name' => 'Category Two',
            'name_ar' => 'الفئة الثانية',
            'status' => true,
            'parent_id' => null,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        $subCategoryTwo = Category::query()->create([
            'name' => 'Sub Category Two',
            'name_ar' => 'الفئة الفرعية الثانية',
            'status' => true,
            'parent_id' => $categoryTwo->id,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        $appServiceOne = AppService::query()->create([
            'name' => 'Service One',
            'description' => 'Service One description',
            'category_id' => $categoryOne->id,
            'sub_category_id' => $subCategoryOne->id,
            'status' => 'active',
            'is_online' => true,
            'price_type' => 'fixed',
            'base_price' => 10,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        $appServiceTwo = AppService::query()->create([
            'name' => 'Service Two',
            'description' => 'Service Two description',
            'category_id' => $categoryTwo->id,
            'sub_category_id' => $subCategoryTwo->id,
            'status' => 'active',
            'is_online' => true,
            'price_type' => 'fixed',
            'base_price' => 15,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        $merchant = Merchant::query()->create([
            'name' => 'Test Merchant',
            'status' => 'active',
            'country_id' => 1,
            'city_id' => 1,
            'area_id' => 1,
            'is_verified' => true,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        $merchantUser = User::factory()->create([
            'type' => User::TYPE_USER,
            'email' => 'merchant@example.com',
            'active' => true,
        ]);

        $merchantUser->merchant_id = $merchant->id;
        $merchantUser->save();
        $merchantUser->syncRoles([2]);

        $merchant->profile_id = $merchantUser->id;
        $merchant->save();

        $response = $this->actingAs($merchantUser, 'sanctum')
            ->postJson('/api/merchant/update', [
                'app_services' => [$appServiceOne->id, $appServiceTwo->id],
            ]);

        $response->assertStatus(400);
        $response->assertJsonPath('errors.0.key', 'app_services');
    }
}
