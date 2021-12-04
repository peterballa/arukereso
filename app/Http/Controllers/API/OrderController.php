<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\OrderCreateRequest;
use App\Jobs\Order\OrderCreatingJob;
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
}
