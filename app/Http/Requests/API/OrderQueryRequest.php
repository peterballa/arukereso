<?php

namespace App\Http\Requests\API;

use App\Service\Order\OrderStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionClass;

class OrderQueryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'nullable|numeric|gt:0|exists:orders,id',
            'status' => [
                'nullable',
                /**
                 * @psalm-suppress UnusedClosureParam
                 */
                function ($attribute, $value, $fail) {
                    $statuses = (new ReflectionClass(OrderStatusEnum::class))->getConstants();

                    if (!in_array($value, array_values($statuses), true)) {
                        $fail('Invalid status! Please try: ' . implode(', ', $statuses));
                    }
                }
            ],
            'createdAtStart' => 'nullable|date|date_format:Y-m-d',
            'createdAtEnd' => 'nullable|date|date_format:Y-m-d',
        ];
    }
}
