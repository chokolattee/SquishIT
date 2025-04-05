<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $order;        
    public $items;        
    public $customer;     
    public $orderStatus;  
    public $grandTotal;
    protected $pdfContent;

    /**
     * Create a new message instance.
     *
     * @param $order
     * @param $items
     * @param $customer
     * @param $grandTotal
     */
    public function __construct($order, $items, $customer, $grandTotal = null)
    {
        $this->order = $order;
        $this->items = $items;
        $this->customer = $customer;
        
        $this->orderStatus = $order->order_status;

        if ($grandTotal === null) {
            $subtotal = $items->sum(function ($item) {
                return $item->quantity * $item->sell_price;
            });

            $shippingRate = $items->isNotEmpty() ? $items[0]->shipping_rate : 0;
            $this->grandTotal = $subtotal + $shippingRate;
        } else {
            $this->grandTotal = $grandTotal;
        }

        $this->pdfContent = Pdf::loadView('email.order_status', [
            'order' => $this->order,
            'items' => $this->items,
            'customer' => $this->customer,
            'orderStatus' => $this->orderStatus, 
            'grandTotal' => $this->grandTotal,
        ])->output();
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@plushit.test', 'Plush-IT'),
            subject: 'Your Order Status Has Been Updated'
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.order_status',
            with: [
                'order' => $this->order,
                'items' => $this->items,
                'customer' => $this->customer,
                'orderStatus' => $this->orderStatus, 
                'grandTotal' => $this->grandTotal,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn() => $this->pdfContent, 'Order_Status_Update.pdf')
                ->withMime('application/pdf'),
        ];
    }
}