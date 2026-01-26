<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\CheckoutController;
use App\Http\Helpers\MailConfig;
use App\Mail\OrderConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $orderInfo;
    protected $invoice;
    /**
     * Create a new job instance.
     */
    public function __construct($orderInfo, $invoice)
    {
        $this->orderInfo = $orderInfo;
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            CheckoutController::OrderMailSend($this->orderInfo, $this->invoice);
        } catch (\Exception $e) {
            \Log::error("Failed to send order confirmation email: " . $e->getMessage());
        }
    }
}
