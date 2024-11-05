<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DarkModeController extends Controller
{
    public function toggle(Request $request)
    {
        $darkMode = $request->session()->get('dark_mode', false);
        $request->session()->put('dark_mode', !$darkMode);
        return response()->json(['dark_mode' => !$darkMode]);
    }
}