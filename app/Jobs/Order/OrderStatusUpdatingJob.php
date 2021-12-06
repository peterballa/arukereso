<?php

namespace App\Jobs\Order;

use App\Http\Requests\API\OrderStatusUpdateRequest;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatingJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private OrderStatusUpdateRequest $request;
    private Order $order;

    public function __construct(Order $order, OrderStatusUpdateRequest $request)
    {
        $this->order = $order;
        $this->request = $request;
    }

    public function handle(): void
    {
        $this->order
            ->setStatus($this->request->get('status'))
            ->save();
    }
}
