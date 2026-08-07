<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        if (!$setting) {
            $setting = Setting::create([
                'pharmacy_name'  => 'Afzaal Pharmacy',
                'currency'       => 'PKR',
                'currency_symbol'=> '₨',
                'invoice_prefix' => 'INV',
                'tax'            => 0,
            ]);
        }
        return view('settings.index', compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $data = $request->validate([
            'pharmacy_name'  => 'required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string|max:500',
            'tax'              => 'required|numeric|min:0|max:100',
            'default_discount' => 'required|numeric|min:0|max:100',
            'currency'         => 'required|string|max:10',
            'currency_symbol'=> 'required|string|max:5',
            'invoice_prefix' => 'required|string|max:10',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        } else {
            unset($data['logo']);
        }

        $setting->update($data);
        return back()->with('success', 'Settings saved successfully.');
    }
}
