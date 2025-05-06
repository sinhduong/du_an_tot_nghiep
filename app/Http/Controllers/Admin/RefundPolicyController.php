<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundPolicy;
use App\Models\RefundTransaction;
use Illuminate\Http\Request;

class RefundPolicyController extends BaseAdminController
{
    public function __construct()
    {
        $this->middleware('permission:refund_policies_list')->only(['index']);
        $this->middleware('permission:refund_policies_create')->only(['create', 'store']);
        $this->middleware('permission:refund_policies_update')->only(['edit', 'update']);
        $this->middleware('permission:refund_policies_delete')->only(['destroy']);
    }
    public function index()
    {
        $title = 'Danh sách chính sách hoàn tiền';
        $refundPolicies = RefundPolicy::paginate(10);
        return view('admins.refund-policies.index', compact('title', 'refundPolicies'));
    }

    public function create()
    {
        $title = 'Thêm mới chính sách hoàn tiền';
        return view('admins.refund-policies.create', compact('title'));
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'refund_percentage' => 'required|numeric|min:0|max:100',
            'days_before_checkin' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['cancellation_fee_percentage'] = $validated['refund_percentage'] > 0 ? 100 - $validated['refund_percentage'] : 0;
        
        RefundPolicy::create($validated);

        return redirect()->route('admin.refund-policies.index')
            ->with('success', 'Thêm mới chính sách hoàn tiền thành công!');
    }

    public function edit(RefundPolicy $refundPolicy, $id)
    {
        $title = 'Chỉnh sửa chính sách hoàn tiền';
        $refundPolicy = RefundPolicy::find($id);
        return view('admins.refund-policies.edit', compact('title', 'refundPolicy'));

    }

    public function update(Request $request, $id)
    {
        $refundPolicy = RefundPolicy::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'refund_percentage' => 'required|numeric|min:0|max:100',
            'days_before_checkin' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['cancellation_fee_percentage'] = $validated['refund_percentage'] > 0 ? 100 - $validated['refund_percentage'] : 0;

        $refundPolicy->update($validated);

        return redirect()->route('admin.refund-policies.index')
            ->with('success', 'Cập nhật chính sách hoàn tiền thành công!');
    }

    public function destroy($id)
    {
        $refundPolicy = RefundPolicy::findOrFail($id);
        $refundPolicy->delete();
        return redirect()->route('admin.refund-policies.index')
            ->with('success', 'Xóa chính sách hoàn tiền thành công!');
    }
} 