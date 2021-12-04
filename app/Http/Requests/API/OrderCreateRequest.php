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
            'delivery_mode' => 'required|string|max:255',
            'invoice_name' => 'required|string|max:255',
            'invoice_zip_code' => 'required|max:4',
            'invoice_city' => 'required|string|max:255',
            'invoice_address' => 'required|string|max:255',
            'delivery_name' => 'required|string|max:255',
            'delivery_zip_code' => 'required|string|max:4',
            'delivery_city' => 'required|string|max:255',
            'delivery_address' => 'required|string|max:255',
            'products.*.name' => 'required|string|max:255',
            'products.*.quantity' => 'required|numeric|gt:0',
            'products.*.gross_price' => 'required|numeric|gt:0',
        ];
    }
}
