<?php

namespace App\Jobs\Order;

use App\Http\Requests\API\OrderCreateRequest;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderCreatingJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private OrderCreateRequest $request;

    public function __construct(OrderCreateRequest $request)
    {
        $this->request = $request;
    }

    public function handle(): int
    {
        $order = (new Order())
            ->setName($this->request->get('name'))
            ->setEmail($this->request->get('email'))
            ->setDeliveryMode($this->request->get('deliveryMode'))
            ->setInvoiceName($this->request->get('invoiceName'))
            ->setInvoiceZipCode($this->request->get('invoiceZipCode'))
            ->setInvoiceCity($this->request->get('invoiceCity'))
            ->setInvoiceAddress($this->request->get('invoiceAddress'))
            ->setDeliveryName($this->request->get('deliveryName'))
            ->setDeliveryZipCode($this->request->get('deliveryZipCode'))
            ->setDeliveryCity($this->request->get('deliveryCity'))
            ->setDeliveryAddress($this->request->get('deliveryAddress'))
            ->setStatus('Új')//TODO crearte enum
            ->save();

        //TODO insert order products
//        foreach()
    }
}
