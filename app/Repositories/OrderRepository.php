<?php

namespace App\Repositories;

use App\Models\Order;
use DateTimeImmutable;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

        if ($status !== null) {
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
