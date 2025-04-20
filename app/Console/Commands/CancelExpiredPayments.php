<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelExpiredPayments extends Command
{
    protected $signature = 'payments:cancel-expired';
    protected $description = 'Cancel bookings with expired VNPay payments';

    public function handle()
    {
        try {
            $expiredTime = Carbon::now()->subMinutes(15);
            
            $this->info("Checking for expired payments before {$expiredTime}");
            
            $expiredPayments = Payment::where('status', 'pending')
                ->where('created_at', '<=', $expiredTime)
                ->get();

            $this->info("Found {$expiredPayments->count()} expired payments");

            foreach ($expiredPayments as $payment) {
                try {
                    $booking = $payment->booking;
                    if ($booking && $booking->status === 'unpaid') {
                        $booking->update(['status' => 'cancelled']);
                        $payment->update(['status' => 'failed']);
                        
                        $message = "Cancelled booking #{$booking->id} due to expired payment";
                        $this->info($message);
                        Log::info($message);
                    }
                } catch (\Exception $e) {
                    $errorMessage = "Error processing payment #{$payment->id}: " . $e->getMessage();
                    $this->error($errorMessage);
                    Log::error($errorMessage);
                }
            }

            $this->info('Expired payments check completed');
        } catch (\Exception $e) {
            $errorMessage = "Error in CancelExpiredPayments command: " . $e->getMessage();
            $this->error($errorMessage);
            Log::error($errorMessage);
        }
    }
} 