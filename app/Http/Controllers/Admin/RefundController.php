<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\RefundTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundController extends BaseAdminController
{
    public function showApproveForm(Refund $refund)
    {
        return view('admins.refunds.approve-form', compact('refund'));
    }

    public function approveRefund(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $refund = Refund::find($id);

            if ($refund->status !== 'pending') {
                return redirect()->back()->with('error', 'Yêu cầu hoàn tiền không ở trạng thái chờ phê duyệt.');
            }

            $action = $request->input('action');
            $adminNote = $request->input('admin_note');
            $refundMethod = $request->input('refund_method');
            $transactionId = $request->input('transaction_id');

            if ($action === 'approve') {
                try {
                    // Cập nhật trạng thái hoàn tiền
                    if (!$refundMethod) {
                        return redirect()->back()->with('success', 'Không được bỏ trống phương thức hoàn tiền.');
                    }
                    $refund->update([
                        'status' => 'approved',
                        'admin_notes' => $adminNote,
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                        'refund_method' => $refundMethod,
                        'transaction_id' => $transactionId
                    ]);

                    Log::info('Updated refund status to approved for refund ID: ' . $refund->id);

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

                    Log::info('Created refund transaction for refund ID: ' . $refund->id);

                    // Cập nhật trạng thái booking
                    $refund->booking->update(['status' => 'refunded']);
                    Log::info('Updated booking status to refunded for booking ID: ' . $refund->booking->id);

                    DB::commit();
                    Log::info('Successfully approved refund for refund ID: ' . $refund->id);

                    return redirect()->back()->with('success', 'Đã phê duyệt yêu cầu hoàn tiền thành công.');
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Error during refund approval process: ' . $e->getMessage());
                    Log::error('Stack trace: ' . $e->getTraceAsString());
                    throw $e;
                }
            } else {
                try {
                    // Từ chối hoàn tiền
                    $refund->update([
                        'status' => 'rejected',
                        'admin_notes' => $adminNote
                    ]);

                    Log::info('Updated refund status to rejected for refund ID: ' . $refund->id);

                    // Tạo giao dịch từ chối
                    $refundTransaction = new RefundTransaction([
                        'refund_id' => $refund->id,
                        'transaction_type' => 'refund_reject',
                        'amount' => $refund->amount,
                        'status' => 'failed',
                        'notes' => 'Từ chối yêu cầu hoàn tiền cho đặt phòng #' . $refund->booking->booking_code
                    ]);
                    $refundTransaction->save();

                    Log::info('Created rejection transaction for refund ID: ' . $refund->id);

                    // Cập nhật trạng thái booking về trạng thái trước đó
                    $refund->booking->update(['status' => 'cancelled']);

                    Log::info('Updated booking status to cancelled for booking ID: ' . $refund->booking->id);

                    DB::commit();
                    Log::info('Successfully rejected refund for refund ID: ' . $refund->id);

                    return redirect()->back()->with('success', 'Đã từ chối yêu cầu hoàn tiền.');
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Error during refund rejection process: ' . $e->getMessage());
                    Log::error('Stack trace: ' . $e->getTraceAsString());
                    throw $e;
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing refund: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xử lý yêu cầu hoàn tiền: ' . $e->getMessage());
        }
    }

    public function getRefundDetails(Refund $refund)
    {
        try {
            Log::info('Getting refund details for refund ID: ' . $refund->id);

            // Load relationships
            $refund->load(['booking.user', 'refundPolicy']);
            Log::info('Loaded relationships for refund: ' . $refund->id);

            // Check if relationships are loaded
            if (!$refund->booking) {
                Log::error('Booking not found for refund: ' . $refund->id);
                throw new \Exception('Booking not found');
            }

            if (!$refund->refundPolicy) {
                Log::error('Refund policy not found for refund: ' . $refund->id);
                throw new \Exception('Refund policy not found');
            }

            $response = [
                'id' => $refund->id,
                'booking' => [
                    'booking_code' => $refund->booking->booking_code,
                    'user' => [
                        'name' => $refund->booking->user->name
                    ],
                    'check_in' => $refund->booking->check_in->format('d/m/Y'),
                    'check_out' => $refund->booking->check_out->format('d/m/Y'),
                    'total_price' => $refund->booking->total_price,
                    'paid_amount' => $refund->booking->paid_amount
                ],
                'refund_policy' => [
                    'name' => $refund->refundPolicy->name
                ],
                'amount' => $refund->amount,
                'cancellation_fee' => $refund->cancellation_fee,
                'reason' => $refund->reason
            ];

            Log::info('Successfully prepared response for refund: ' . $refund->id);
            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Error getting refund details: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Không thể lấy thông tin chi tiết hoàn tiền: ' . $e->getMessage()
            ], 500);
        }
    }
}
