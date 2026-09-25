<?php

namespace Tests\Feature\AppService;

use App\Domains\AppService\Http\Transformers\AppServiceTransformer;
use App\Domains\AppService\Models\AppService;
use Tests\TestCase;

class AppServiceTransformerTest extends TestCase
{
    public function test_transform_returns_variant_and_option_images_as_urls(): void
    {
        $appService = new AppService([
            'name' => 'Test service',
            'variants' => [
                [
                    'name' => 'Size',
                    'type' => 'single',
                    'required' => 'required',
                    'image' => 'app-services/variants/variant.png',
                    'options' => [
                        [
                            'name' => 'Small',
                            'price' => 10,
                            'discount_price' => 5,
                            'image' => 'app-services/variants/options/small.png',
                        ],
                    ],
                ],
            ],
        ]);

        $data = (new AppServiceTransformer())->transform($appService);

        $this->assertArrayHasKey('variants', $data);
        $this->assertStringContainsString('app-services/variants/variant.png', $data['variants'][0]['image'] ?? '');
        $this->assertStringContainsString('app-services/variants/options/small.png', $data['variants'][0]['options'][0]['image'] ?? '');
    }
}
