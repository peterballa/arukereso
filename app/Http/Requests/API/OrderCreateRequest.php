<?php

namespace App\Http\Requests\API;

use App\Service\Order\DeliveryModeEnum;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionClass;

class OrderCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email',
            'deliveryMode' => [
                'required',
                'string',
                'max:255',
                /**
                 * @psalm-suppress UnusedClosureParam
                 */
                function ($attribute, $value, $fail) {
                    $deliveryModes = (new ReflectionClass(DeliveryModeEnum::class))->getConstants();

                    if (!in_array($value, array_values($deliveryModes), true)) {
                        $fail('Invalid delivery mode! Please try: ' . implode(', ', $deliveryModes));
                    }
                }
            ],
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
