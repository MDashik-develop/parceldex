<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MerchantPaymentInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $merchant;
    public $amount;
    public $invoiceId;
    public $path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($merchant, $amount, $invoiceId, $path)
    {
        $this->merchant = $merchant;
        $this->amount = $amount;
        $this->invoiceId = $invoiceId;
        $this->path = $path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $merchant = $this->merchant;
        // $amount = $this->amount;
        // $invoiceId = $this->invoiceId;
        // return $this->markdown('admin.account.merchantDeliveryPayment.printMerchantDeliveryPaymentUpdated', compact('merchant', 'amount', 'invoiceId'));

        $email = $this->view('admin.account.merchantDeliveryPayment.printMerchantDeliveryPaymentUpdated')
            ->with([
                'merchant' => $this->merchant,
                'amount' => $this->amount,
                'invoiceId' => $this->invoiceId,
            ])->subject('Merchant Payment Invoice - ' . now()->format('d/m/Y'));

        if ($this->path) {
            $email->attach($this->path);
        }

        return $email;
    }
}
