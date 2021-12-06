<?php

namespace App\Repositories;

use App\Models\Order;
use App\Service\Order\OrderStatusEnum;
use DateTimeImmutable;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use ReflectionClass;

class OrderRepository
{
    /**
     * @throws Exception
     */
    public function find(
        ?int $orderId,
        ?string $status,
        ?DateTimeImmutable $createdAtStart,
        DateTimeImmutable $createdAtEnd
    ): LengthAwarePaginator {
        $query = Order::query()
            ->select(['id', 'status', 'name', 'created_at']);

        if ($orderId !== null) {
            $query->where('id', $orderId);
        }

        $statuses = (new ReflectionClass(OrderStatusEnum::class))->getConstants();

        if ($status !== null) {
            if (!in_array($status, array_values($statuses), true)) {
                throw new Exception('Invalid status! Please try: ' . implode(', ', $statuses));
            }

            $query->where('status', $status);
        }

        if ($createdAtStart !== null) {
            $query->where('created_at', '>', $createdAtStart);
        }

        $query->where('created_at', '<', $createdAtEnd);

        $orders = $query->paginate(20);

        /** @var Order $order */
        foreach ($orders as $order) {
            $order->append(['gross_total']);
        }

        return $orders;
    }
}
