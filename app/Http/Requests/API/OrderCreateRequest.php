<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email',
            'deliveryMode' => 'required|string|max:255',
            'invoiceName' => 'required|string|max:255',
            'invoiceZipCode' => 'required|max:4',
            'invoiceCity' => 'required|string|max:255',
            'invoiceAddress' => 'required|string|max:255',
            'deliveryName' => 'required|string|max:255',
            'deliveryZipCode' => 'required|string|max:4',
            'deliveryCity' => 'required|string|max:255',
            'deliveryAddress' => 'required|string|max:255',
            'products.*.name' => 'required|string|max:255',
            'products.*.quantity' => 'required|numeric|gt:0',
            'products.*.grossPrice' => 'required|numeric|gt:0',
        ];
    }
}
