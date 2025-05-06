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

            // Kiểm tra trạng thái thanh toán của booking
            if ($booking->status == 'unpaid') {
                Log::info('Booking is unpaid, cancelling without refund');
                Log::info('Current booking status before update: ' . $booking->status);
                Log::info('Booking ID: ' . $booking->id);

                // Update trạng thái booking
                $booking->status = 'cancelled_without_refund';
                $booking->save();

                Log::info('Booking status updated to cancelled_without_refund');
                DB::commit();
                return redirect()->back()->with('success', 'Đặt phòng chưa thanh toán đã được hủy.');
            }

            // Kiểm tra xem booking đã có yêu cầu hoàn tiền chưa
            if ($booking->refund) {
                Log::warning('Booking already has a refund request: ' . $booking->id);
                return redirect()->back()->with('error', 'Đã có yêu cầu huỷ cho đặt phòng này.');
            }

            // Kiểm tra chính sách hoàn tiền
            $daysBeforeCheckin = now()->diffInDays($booking->check_in);
            Log::info('Days before checkin: ' . $daysBeforeCheckin);

            $policy = RefundPolicy::where('days_before_checkin', '<=', $daysBeforeCheckin)
                ->where('is_active', true)
                ->orderBy('days_before_checkin', 'desc')
                ->first();

                // dd($policy);

            if (!$policy) {
                Log::warning('No suitable refund policy found for booking: ' . $booking->id);
                $booking->status = 'cancelled_without_refund';
                $booking->save();
                DB::commit();
                return redirect()->back()->with('success', 'Phòng đã được huỷ và không đủ điều kiện hoàn tiền.');
            }

            Log::info('Found refund policy: ' . $policy->id);

            // Xử lý trường hợp hủy trong 24 giờ (không hoàn tiền)
            if ($policy->days_before_checkin == 0 && $policy->refund_percentage == 0) {
                Log::info('Booking cancelled within 24 hours, no refund');

                // Tạo yêu cầu hoàn tiền với số tiền hoàn = 0
                $refund = new Refund([
                    'booking_id' => $booking->id,
                    'refund_policy_id' => $policy->id,
                    'reason' => $request->input('reason'),
                    'amount' => 0,
                    'cancellation_fee' => $booking->status === 'partial' ? $booking->paid_amount : $booking->total_amount,
                    'status' => 'approved'
                ]);

                $refund->save();

                // Tạo giao dịch hoàn tiền
                $refundTransaction = new RefundTransaction([
                    'refund_id' => $refund->id,
                    'transaction_type' => 'refund',
                    'amount' => 0,
                    'status' => 'completed',
                    'payment_method' => 'none',
                    'notes' => 'Hủy trong 24 giờ - Không hoàn tiền cho đặt phòng #' . $booking->booking_code
                ]);

                $refundTransaction->save();

                // Cập nhật trạng thái booking
                $booking->status = 'cancelled_without_refund';
                $booking->save();

                DB::commit();
                return redirect()->back()->with('success', 'Đặt phòng đã được hủy. Lưu ý: Hủy trong 24 giờ không được hoàn tiền.');
            }

            // Tính toán số tiền hoàn và phí hủy
            $paidAmount = $booking->status === 'partial' ? $booking->paid_amount : $booking->total_price;
            $amount = $paidAmount * ($policy->refund_percentage / 100);
            $cancellationFee = $paidAmount * ($policy->cancellation_fee_percentage / 100);
//            dd($amount, $paidAmount, ($policy->refund_percentage / 100), $cancellationFee);

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
            $booking->status = 'cancelled';
            $booking->save();

            DB::commit();
            Log::info('Refund request completed successfully');

            return redirect()->back()->with('success', 'Yêu cầu đã được gửi thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error requesting refund: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi gửi yêu cầu: ' . $e->getMessage());
        }
    }

    public function lists()
    {
        $refunds = Refund::with(['booking', 'refundPolicy'])
            ->whereHas('booking', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('clients.refunds.lists', compact('refunds'));
    }
}
