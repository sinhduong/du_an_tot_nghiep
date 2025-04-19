<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\RefundTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundController extends Controller
{
    public function showApproveForm(Refund $refund)
    {
        return view('admins.refunds.approve-form', compact('refund'));
    }

    public function approveRefund(Request $request, Refund $refund)
    {
        try {
            DB::beginTransaction();

            if ($refund->status !== 'pending') {
                return redirect()->back()->with('error', 'Yêu cầu hoàn tiền không ở trạng thái chờ phê duyệt.');
            }

            $action = $request->input('action');
            $adminNote = $request->input('admin_note');
            $refundMethod = $request->input('refund_method');
            $transactionId = $request->input('transaction_id');

            if ($action === 'approve') {
                // Cập nhật trạng thái hoàn tiền
                $refund->update([
                    'status' => 'approved',
                    'admin_notes' => $adminNote,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'refund_method' => $refundMethod,
                    'transaction_id' => $transactionId
                ]);

                // Tạo giao dịch hoàn tiền
                $refundTransaction = new RefundTransaction([
                    'refund_id' => $refund->id,
                    'transaction_type' => 'refund',
                    'amount' => $refund->amount,
                    'status' => 'completed',
                    'payment_method' => $refundMethod,
                    'transaction_id' => $transactionId,
                    'notes' => 'Hoàn tiền cho đặt phòng #' . $refund->booking->booking_code
                ]);
                $refundTransaction->save();

                // Cập nhật trạng thái booking
                $refund->booking->update(['status' => 'refunded']);

                DB::commit();
                return redirect()->route('admins.bookings.index')->with('success', 'Đã phê duyệt yêu cầu hoàn tiền thành công.');
            } else {
                // Từ chối hoàn tiền
                $refund->update([
                    'status' => 'rejected',
                    'admin_notes' => $adminNote
                ]);

                // Tạo giao dịch từ chối
                $refundTransaction = new RefundTransaction([
                    'refund_id' => $refund->id,
                    'transaction_type' => 'refund_reject',
                    'amount' => $refund->amount,
                    'status' => 'failed',
                    'notes' => 'Từ chối yêu cầu hoàn tiền cho đặt phòng #' . $refund->booking->booking_code
                ]);
                $refundTransaction->save();

                // Cập nhật trạng thái booking về trạng thái trước đó
                $refund->booking->update(['status' => 'cancelled']);

                DB::commit();
                return redirect()->route('admins.bookings.index')->with('success', 'Đã từ chối yêu cầu hoàn tiền.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing refund: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xử lý yêu cầu hoàn tiền.');
        }
    }
} 