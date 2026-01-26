<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\CheckoutController;
use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderInfo;
    protected $cartData;

    /**
     * Create a new job instance.
     */
    public function __construct($orderInfo, $cartData)
    {
        $this->orderInfo = $orderInfo;
        $this->cartData = $cartData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Process Transactions
        TransactionController::product_purchase($this->orderInfo);

        // Generate Invoice
        $invoice = CheckoutController::generateInvoice($this->orderInfo);

        // Send Email (Queued)
        SendOrderConfirmationEmail::dispatch($this->orderInfo, $invoice);
    }
}
