<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'deliveryMode' => 'string',
        'invoiceName' => 'string',
        'invoiceZipCode' => 'string',
        'invoiceCity' => 'string',
        'invoiceAddress' => 'string',
        'deliveryName' => 'string',
        'deliveryZipCode' => 'string',
        'deliveryCity' => 'string',
        'deliveryAddress' => 'string',
        'status' => 'string'
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): Order
    {
        $this->name = $name;

        return $this;
    }

    public function setEmail(string $email): Order
    {
        $this->email = $email;

        return $this;
    }

    public function setDeliveryMode(string $deliveryMode): Order
    {
        $this->delivery_mode = $deliveryMode;

        return $this;
    }

    public function setInvoiceName(string $invoiceName): Order
    {
        $this->invoice_name = $invoiceName;

        return $this;
    }

    public function setInvoiceZipCode(string $invoiceZipCode): Order
    {
        $this->invoice_zip_code = $invoiceZipCode;

        return $this;
    }

    public function setInvoiceCity(string $invoiceCity): Order
    {
        $this->invoice_city = $invoiceCity;

        return $this;
    }

    public function setInvoiceAddress(string $invoiceAddress): Order
    {
        $this->invoice_address = $invoiceAddress;

        return $this;
    }

    public function setDeliveryName(string $deliveryName): Order
    {
        $this->delivery_name = $deliveryName;

        return $this;
    }

    public function setDeliveryZipCode(string $deliveryZipCode): Order
    {
        $this->delivery_zip_code = $deliveryZipCode;

        return $this;
    }

    public function setDeliveryCity(string $deliveryCity): Order
    {
        $this->delivery_city = $deliveryCity;

        return $this;
    }

    public function setDeliveryAddress(string $deliveryAddress): Order
    {
        $this->delivery_address = $deliveryAddress;

        return $this;
    }

    public function setStatus(string $status): Order
    {
        $this->status = $status;

        return $this;
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }
}
