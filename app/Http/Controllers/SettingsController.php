<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function save_timezone(Request $request)
    {
        $timezone_offset_minutes = $request['timezone_difference'];
        $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes*60, false);
        Settings::create([
            'default_timezone' => $timezone_name
        ]);
        date_default_timezone_set('UTC');
        return redirect('/tasks');
    }
}
