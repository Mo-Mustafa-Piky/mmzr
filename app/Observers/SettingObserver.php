<?php

namespace App\Observers;

use App\Models\Setting;

class SettingObserver
{
    public function creating(Setting $setting): void
    {
        if (Setting::exists()) {
            abort(403, 'Only one setting record is allowed.');
        }
    }

    public function deleting(Setting $setting): void
    {
        abort(403, 'Settings cannot be deleted.');
    }
}
