<?php

namespace App\Domains\Setting\Services;

use App\Domains\Setting\Models\Setting;

class SettingService
{
    public function __construct(protected Setting $setting)
    {
    }

    public function all()
    {
        return $this->setting->newQuery()
            ->orderBy('group')
            ->orderBy('key')
            ->get();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->setting->newQuery()->where('key', $key)->value('value');

        return $value ?? $default;
    }

    public function set(string $key, mixed $value, string $type = 'string', string $group = 'general'): Setting
    {
        $setting = $this->setting->newQuery()->firstOrNew(['key' => $key]);

        $setting->fill([
            'value' => $this->normalizeValue($value),
            'type' => $type,
            'group' => $group,
        ]);

        $setting->save();

        return $setting;
    }

    protected function normalizeValue(mixed $value): string|null
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        return (string) $value;
    }
}
