<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display the System Settings / School Profile page.
     */
    public function index(): View
    {
        // Pluck converts the database rows into a simple key-value array.
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update and save the system settings.
     */
    public function store(Request $request)
    {
        $request->validate([
            'school_name'         => ['nullable', 'string', 'max:255'],
            'school_id'           => ['nullable', 'string', 'max:50'],
            'default_school_year' => ['nullable', 'string', 'max:20'],
            'principal_name'      => ['nullable', 'string', 'max:255'],
            'school_address'      => ['nullable', 'string', 'max:500'],
            'school_logo'         => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($request->hasFile('school_logo')) {
            $file = $request->file('school_logo');
            $file->move(public_path('images'), 'school-logo.png');
            Setting::set('has_custom_logo', 'true');
        }

        $inputsToSave = $request->except(['_token', 'school_logo']);

        foreach ($inputsToSave as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'School Profile and System Settings updated successfully.');
    }
}
