<?php

namespace Modules\Setting\Traits;


trait SettingTrait
{

    public function scopeSetting($query, $name)
    {
        if (str_contains($name, ".")) {
            $item_array = explode(".", $name);
            $setting = $query->whereName($item_array[0])->first();
            if ($setting->value == null) return null;
            $item_value = (object) $setting->value;
            return $item_value->{$item_array[1]};
        } else {
            $setting = $query->whereName($name)->first();
            if ($setting->value == null) return null;
            if (is_array($setting->value))  return (object) $setting->value;
            return $setting->value;
        }
    }

    public function scopeSet($query, $items)
    {
        return $query->upsert($items, ['name'], ['value']);
    }


    public function scopeGetAll($query)
    {
        $settings = $query->get();
        $settings = collect($settings)->toArray();
        return (object) array_column($settings, 'value', 'name');
    }
}
