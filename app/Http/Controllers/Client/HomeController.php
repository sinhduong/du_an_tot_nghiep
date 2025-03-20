<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Faq;
use App\Models\Introduction;
use App\Models\Service;
use App\Models\System;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $system =  System::where('is_use',1)->get();
        // dd($system);
        return view('clients.home',compact('system'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function faqs()
    {
        $faqs = Faq::where('is_active', 1)->get();
        return view('clients.faq', compact('faqs'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function services()
    {
        $services = Service::where('is_active', 1)->get();
        return view('clients.service', compact('services'));
    }

    /**
     * Display the specified resource.
     */
    public function abouts()
    {
        $about = About::where('is_use', 1)->first();
        if (!$about) {
            $about = new About();
            $about->about = 'Chưa có nội dung nào được thiết lập.';
        }
        return view('clients.about', compact('about'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function introductions()
    {
        $introduction = Introduction::where('is_use', 1)->first();
        if (!$introduction) {
            $introduction = new About();
            $introduction->introduction = 'Chưa có nội dung nào được thiết lập.';
        }
        return view('clients.introduction', compact('introduction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
