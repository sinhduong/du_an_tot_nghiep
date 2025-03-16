<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Models\Admin\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Faq::query();

        if ($request->has('question') && !empty($request->question)) {
            $query->where('question', 'like', '%' . $request->question . '%');
        }

        $perPage = $request->size ?? 20;

        $faqs = $query->latest()->paginate($perPage)->appends($request->query());

        return view('admins.faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.faqs.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFaqRequest $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'boolean'
        ]);

        Faq::create($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'Tạo mới "Câu hỏi thường gặp" thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        return view('admins.faqs.form', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'Cập nhật "Câu hỏi thường gặp" thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')
            ->with('success', 'Xóa bỏ "Câu hỏi thường gặp" thành công');
    }
}
