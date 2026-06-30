<?php

namespace App\Domains\Delivery\Services;

use App\Domains\AppService\Models\AppService;
use InvalidArgumentException;

class AppServiceVariantResolver
{
    /**
     * Validate customer variant selections and build a price snapshot for the order.
     *
     * @param  array<int, array{name: string, selected_options: array<int, string>}>  $selections
     * @return array{selected_variants: array<int, array>, price: float, total_price: float}
     */
    public function resolve(AppService $appService, array $selections): array
    {
        $variantDefinitions = $this->normalizeVariants($appService->variants);

        if (empty($variantDefinitions)) {
            throw new InvalidArgumentException(__('This app service has no configurable variants.'));
        }

        $selectionsByName = collect($selections)->keyBy('name');
        $snapshot = [];
        $optionsTotal = 0.0;

        foreach ($variantDefinitions as $definition) {
            $variantName = $definition['name'];
            $selection = $selectionsByName->get($variantName);
            $selectedOptionNames = $selection['selected_options'] ?? [];

            if (($definition['required'] ?? 'optional') === 'required' && empty($selectedOptionNames)) {
                throw new InvalidArgumentException(__('The :variant variant is required.', ['variant' => $variantName]));
            }

            if (empty($selectedOptionNames)) {
                continue;
            }

            if (($definition['type'] ?? 'single') === 'single' && count($selectedOptionNames) > 1) {
                throw new InvalidArgumentException(__('Only one option can be selected for :variant.', ['variant' => $variantName]));
            }

            $resolvedOptions = [];

            foreach ($selectedOptionNames as $optionName) {
                $option = collect($definition['options'] ?? [])->firstWhere('name', $optionName);

                if (!$option) {
                    throw new InvalidArgumentException(__('Invalid option :option for variant :variant.', [
                        'option' => $optionName,
                        'variant' => $variantName,
                    ]));
                }

                $unitPrice = $this->resolveOptionPrice($option);
                $optionsTotal += $unitPrice;

                $resolvedOptions[] = [
                    'name' => $optionName,
                    'price' => round($unitPrice, 2),
                ];
            }

            $snapshot[] = [
                'name' => $variantName,
                'type' => $definition['type'] ?? 'single',
                'required' => $definition['required'] ?? 'optional',
                'selected_options' => $resolvedOptions,
            ];
        }

        $unknownSelections = $selectionsByName->keys()->diff(collect($variantDefinitions)->pluck('name'));
        if ($unknownSelections->isNotEmpty()) {
            throw new InvalidArgumentException(__('Unknown variant :variant.', ['variant' => $unknownSelections->first()]));
        }

        $basePrice = $this->resolveBasePrice($appService);
        $totalPrice = round($basePrice + $optionsTotal, 2);

        return [
            'selected_variants' => $snapshot,
            'price' => $basePrice,
            'total_price' => $totalPrice,
        ];
    }

    private function normalizeVariants(mixed $variants): array
    {
        if (is_string($variants)) {
            $decoded = json_decode($variants, true);

            return is_array($decoded) ? $decoded : [];
        }

        return is_array($variants) ? $variants : [];
    }

    private function resolveBasePrice(AppService $appService): float
    {
        $basePrice = (float) ($appService->base_price ?? 0);
        $discount = (float) ($appService->discount ?? 0);

        if ($discount > 0) {
            $basePrice = max(0, $basePrice - $discount);
        }

        return round($basePrice, 2);
    }

    private function resolveOptionPrice(array $option): float
    {
        $discountPrice = (float) ($option['discount_price'] ?? 0);
        $price = (float) ($option['price'] ?? 0);

        if ($discountPrice > 0) {
            return $discountPrice;
        }

        return $price;
    }
}
