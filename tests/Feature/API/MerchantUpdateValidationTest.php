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

    /** @test */
    public function hidden_items_are_excluded_from_public_api_lists()
    {
        $categoryVisible = Category::query()->create([
            'name' => 'Visible Category',
            'name_ar' => 'فئة مرئية',
            'status' => true,
            'parent_id' => null,
            'is_hidden_from_api' => false,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        Category::query()->create([
            'name' => 'Hidden Category',
            'name_ar' => 'فئة مخفية',
            'status' => true,
            'parent_id' => null,
            'is_hidden_from_api' => true,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        $appServiceVisible = AppService::query()->create([
            'name' => 'Visible Service',
            'description' => 'Visible service description',
            'category_id' => $categoryVisible->id,
            'status' => 'active',
            'is_online' => true,
            'is_hidden_from_api' => false,
            'price_type' => 'fixed',
            'base_price' => 10,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        AppService::query()->create([
            'name' => 'Hidden Service',
            'description' => 'Hidden service description',
            'category_id' => $categoryVisible->id,
            'status' => 'active',
            'is_online' => true,
            'is_hidden_from_api' => true,
            'price_type' => 'fixed',
            'base_price' => 12,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        $visibleMerchant = Merchant::query()->create([
            'name' => 'Visible Merchant',
            'status' => 'active',
            'country_id' => 1,
            'city_id' => 1,
            'area_id' => 1,
            'is_verified' => true,
            'is_hidden_from_api' => false,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        Merchant::query()->create([
            'name' => 'Hidden Merchant',
            'status' => 'active',
            'country_id' => 1,
            'city_id' => 1,
            'area_id' => 1,
            'is_verified' => true,
            'is_hidden_from_api' => true,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ]);

        $categoriesResponse = $this->getJson('/api/lookups/getCategories');
        $categoriesResponse->assertOk();
        $this->assertCount(1, $categoriesResponse->json('data'));
        $this->assertSame('Visible Category', $categoriesResponse->json('data.0.name'));

        $servicesResponse = $this->getJson('/api/app-services?category_id=' . $categoryVisible->id);
        $servicesResponse->assertOk();
        $this->assertCount(1, $servicesResponse->json('data'));
        $this->assertSame('Visible Service', $servicesResponse->json('data.0.name'));

        $merchantsResponse = $this->getJson('/api/get-all-merchants');
        $merchantsResponse->assertOk();
        $this->assertCount(1, $merchantsResponse->json('data.data'));
        $this->assertSame('Visible Merchant', $merchantsResponse->json('data.data.0.name'));
    }
}
