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

/**
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
 */
class OrderController extends Controller
{
    /**
     *
     * @OA\Post(
     * path="/api/orders/create",
     * summary="Create order",
     * description="Create order with name, email, products etc.",
     * tags={"Orders"},
     * security={{"bearer_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       required={"name","email", "deliveryMode", "invoiceName", "invoiceZipCode", "invoiceCity", "invoiceAddress",
     *                  "deliveryName", "deliveryZipCode", "deliveryCity", "deliveryAddress"},
     *       @OA\Property(property="name", type="string", example="John Doe"),
     *       @OA\Property(property="email", type="string", format="email", example="john.doe@gmail.com"),
     *       @OA\Property(property="deliveryMode", type="string", example="personal"),
     *       @OA\Property(property="invoiceName", type="string", example="Anonym Ltd."),
     *       @OA\Property(property="invoiceZipCode", type="string", example="4033"),
     *       @OA\Property(property="invoiceCity", type="string", example="Debrecen"),
     *       @OA\Property(property="invoiceAddress", type="string", example="Test street 5."),
     *       @OA\Property(property="deliveryName", type="string", example="John Doe"),
     *       @OA\Property(property="deliveryZipCode", type="string", example="4033"),
     *       @OA\Property(property="deliveryCity", type="string", example="Debrecen"),
     *       @OA\Property(property="deliveryAddress", type="string", example="Test street 5."),
     *       @OA\Property(property="products", type="array", @OA\Items(
     *          @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="product name 1"
     *                      ),
     *          @OA\Property(
     *                         property="grossPrice",
     *                         type="number",
     *                         example=21
     *                      ),
     *          @OA\Property(
     *                         property="quantity",
     *                         type="number",
     *                         example=12
     *                      )
     *     )),
     *    )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Successful created",
     *     @OA\JsonContent()
     * ),
     * @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * )
     * )
     */
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

    /**
     *
     * @OA\Post(
     * path="/api/orders/query",
     * summary="Query orders",
     * description="Retrieve a list by filter",
     * tags={"Orders"},
     * security={{"bearer_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="integer", example="1"),
     *       @OA\Property(property="status", type="string", example="új"),
     *       @OA\Property(property="createdAtStart", type="string", example="2020-12-04"),
     *       @OA\Property(property="createdAtEnd", type="string", example="2020-12-06"),
     *    )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Successful query",
     *     @OA\JsonContent()
     * ),
     * @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * )
     * )
     */
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

    /**
     *
     * @OA\Post(
     * path="/api/orders/updateStatus/{orderId}",
     * summary="Update order status",
     * description="Retrieve a list by filter",
     * tags={"Orders"},
     * security={{"bearer_token":{}}},
     * @OA\Parameter(
     *    description="ID of order",
     *    in="path",
     *    name="orderId",
     *    required=true,
     *    example=1,
     *    @OA\Schema(
     *       type="integer",
     *       format="int64"
     *    )
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="új"),
     *    )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Successful status update",
     *     @OA\JsonContent()
     * ),
     * @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Model not found",
     *     @OA\JsonContent(
     *       @OA\Property(
     *          property="message",
     *          type="string",
     *          example="No query results for model [App\\Models\\Order] $ORDERID."
     *      )
     *     )
     * )
     * )
     */
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
