<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class PaymentSettingController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:payment_settings_list')->only(['index']);
        $this->middleware('permission:payment_settings_update')->only(['update']);
    }
    
    public function index()
       {
           $setting = PaymentSetting::first();
           return view('admins.payment_settings.index', compact('setting'));
       }

       public function update(Request $request)
       {
           $request->validate([
               'deposit_percentage' => 'required|numeric|min:1|max:99'
           ]);

           $setting = PaymentSetting::firstOrNew();
           $setting->deposit_percentage = $request->deposit_percentage;
           $setting->save();

           return redirect()->back()->with('success', 'Cập nhật tỷ lệ đặt cọc thành công');
       }
}
