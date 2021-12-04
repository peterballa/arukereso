<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends Model
{
    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
        'name' => 'string',
        'gross_price' => 'integer',
        'quantity' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function setOrderId(int $orderId): OrderProduct
    {
        $this->order_id = $orderId;

        return $this;
    }

    public function setName(string $name): OrderProduct
    {
        $this->name = $name;

        return $this;
    }

    public function setGrossPrice(int $grossPrice): OrderProduct
    {
        $this->gross_price = $grossPrice;

        return $this;
    }

    public function setQuantity(int $quantity): OrderProduct
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getOrder(): Order
    {
        return $this->order;
    }
}
