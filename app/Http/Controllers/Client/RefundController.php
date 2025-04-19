<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Refund;
use App\Models\RefundPolicy;
use App\Models\RefundTransaction;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundController extends Controller
{
    public function requestRefund(Request $request, Booking $booking)
    {
        try {
            Log::info('Starting refund request for booking: ' . $booking->id);
            DB::beginTransaction();

            // Kiểm tra xem booking đã có yêu cầu hoàn tiền chưa
            if ($booking->refund) {
                Log::warning('Booking already has a refund request: ' . $booking->id);
                return redirect()->back()->with('error', 'Đã có yêu cầu hoàn tiền cho đặt phòng này.');
            }

            // Kiểm tra chính sách hoàn tiền
            $daysBeforeCheckin = now()->diffInDays($booking->check_in_date);
            Log::info('Days before checkin: ' . $daysBeforeCheckin);

            $policy = RefundPolicy::where('days_before_checkin', '<=', $daysBeforeCheckin)
                ->where('is_active', true)
                ->orderBy('days_before_checkin', 'desc')
                ->first();

            if (!$policy) {
                Log::warning('No suitable refund policy found for booking: ' . $booking->id);
                return redirect()->back()->with('error', 'Không tìm thấy chính sách hoàn tiền phù hợp.');
            }

            Log::info('Found refund policy: ' . $policy->id);

            // Tính toán số tiền hoàn và phí hủy
            $amount = $booking->total_amount * ($policy->refund_percentage / 100);
            $cancellationFee = $booking->total_amount * ($policy->cancellation_fee_percentage / 100);
            
            Log::info('Calculated refund amount: ' . $amount);
            Log::info('Calculated cancellation fee: ' . $cancellationFee);

            // Tạo yêu cầu hoàn tiền
            $refund = new Refund([
                'booking_id' => $booking->id,
                'refund_policy_id' => $policy->id,
                'reason' => $request->input('reason'),
                'amount' => $amount,
                'cancellation_fee' => $cancellationFee,
                'status' => 'pending'
            ]);
            
            Log::info('Saving refund request');
            $refund->save();

            // Lấy phương thức thanh toán từ bảng payments
            $payment = Payment::where('booking_id', $booking->id)
                ->latest()
                ->first();

            if (!$payment) {
                throw new \Exception('Không tìm thấy thông tin thanh toán cho đặt phòng này.');
            }

            // Tạo giao dịch hoàn tiền
            $refundTransaction = new RefundTransaction([
                'refund_id' => $refund->id,
                'transaction_type' => 'refund_request',
                'amount' => $amount,
                'status' => 'pending',
                'payment_method' => $payment->method,
                'notes' => 'Yêu cầu hoàn tiền cho đặt phòng #' . $booking->booking_code
            ]);
            
            Log::info('Saving refund transaction');
            $refundTransaction->save();

            // Cập nhật trạng thái booking
            Log::info('Updating booking status');
            $booking->update(['status' => 'cancelled']);

            DB::commit();
            Log::info('Refund request completed successfully');

            return redirect()->back()->with('success', 'Yêu cầu hoàn tiền đã được gửi thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error requesting refund: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi gửi yêu cầu hoàn tiền: ' . $e->getMessage());
        }
    }
} 