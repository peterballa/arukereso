<?php

namespace App\Http\Requests\API;

use App\Service\Order\OrderStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionClass;

class OrderStatusUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                /**
                 * @psalm-suppress UnusedClosureParam
                 */
                function ($attribute, $value, $fail) {
                    $statuses = (new ReflectionClass(OrderStatusEnum::class))->getConstants();

                    if (!in_array($value, array_values($statuses), true)) {
                        $fail('Invalid status! Please try: ' . implode(', ', $statuses));
                    }
                }
            ]
        ];
    }
}
