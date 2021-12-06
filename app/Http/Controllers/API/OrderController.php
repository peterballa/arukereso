<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\OrderCreateRequest;
use App\Http\Requests\API\OrderQueryRequest;
use App\Http\Requests\API\OrderStatusUpdateRequest;
use App\Jobs\Order\OrderCreatingJob;
use App\Jobs\Order\OrderStatusUpdatingJob;
use App\Models\Order;
use App\Repositories\OrderRepository;
use DateTimeImmutable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function create(OrderCreateRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $orderId = OrderCreatingJob::dispatchNow($request);
            DB::commit();

            return new JsonResponse(['orderId' => $orderId], Response::HTTP_OK);
        } catch (Exception $exception) {
            DB::rollBack();

            return new JsonResponse([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function query(OrderQueryRequest $request, OrderRepository $orderRepository): JsonResponse
    {
        try {
            $orderId = $request->get('id') === null
                ? null
                : (int) $request->get('id');

            $createdAtStart = $request->get('createdAtStart') === null
                ? null
                : new DateTimeImmutable($request->get('createdAtStart'));

            return new JsonResponse(
                $orderRepository->find(
                    $orderId,
                    $request->get('status'),
                    $createdAtStart,
                    new DateTimeImmutable($request->get('createdAtEnd', 'now'))
                ),
                Response::HTTP_OK
            );
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateStatus(OrderStatusUpdateRequest $request, Order $order): JsonResponse
    {
        try {
            OrderStatusUpdatingJob::dispatchNow($order, $request);

            return new JsonResponse(
                [],
                Response::HTTP_OK
            );
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
