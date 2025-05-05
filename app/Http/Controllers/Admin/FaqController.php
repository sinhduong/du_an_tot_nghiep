<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FaqController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:faqs_list')->only(['index']);
        $this->middleware('permission:faqs_create')->only(['create', 'store']);
        $this->middleware('permission:faqs_update')->only(['edit', 'update']);
        $this->middleware('permission:faqs_delete')->only(['destroy']);
    }
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
        if ($request->isMethod('POST')) {
            $data = $request->except('_token', 'image');

            // Xử lý upload hình ảnh
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('faqs', 'public');
            }

            Faq::create($data);
        }

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
        if ($request->isMethod('POST') || $request->isMethod('PUT')) {
            $data = $request->except('_token', '_method', 'image', 'delete_image');

            // Xử lý upload hình ảnh
            if ($request->hasFile('image')) {
                // Xóa hình ảnh cũ nếu có
                if ($faq->image) {
                    Storage::disk('public')->delete($faq->image);
                }
                if ($request->hasFile('image')) {
                    $data['image'] = $request->file('image')->store('banners', 'public');
                }
        
                // Upload hình ảnh mới
                
            } elseif ($request->has('delete_image') && $faq->image) {
                // Xóa hình ảnh nếu checkbox delete_image được chọn
                Storage::disk('public')->delete($faq->image);
                $data['image'] = null;
            }

            $faq->update($data);
        }

        return redirect()->route('admin.faqs.index')
            ->with('success', 'Cập nhật "Câu hỏi thường gặp" thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        if ($faq->image) {
            Storage::disk('public')->delete($faq->image);
        }

        $faq->delete();
        return redirect()->route('admin.faqs.index')
            ->with('success', 'Xóa bỏ "Câu hỏi thường gặp" thành công');
    }
}
