<?php

use App\Models\Order;
use App\Models\User;
use App\Service\Order\OrderStatusEnum;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderStatusUpdateTest extends TestCase
{
    private User $user;
    private string $token;
    private string $uri;
    private Client $client;

    public function setUp(): void
    {
        parent::setUp();
        //Note: I dont like mass assignment, use setter
        $this->user = User::create([
            'name' => 'Test Elek',
            'email' => 'test.email' . time() . '@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
        ]);

        $this->user->save();
        $this->token = $this->user->createToken('myapptoken')->plainTextToken;
        $this->uri = env('APP_URL') . '/api/orders/updateStatus';
        $this->client = new Client();
    }

    public function tearDown(): void
    {
        $this->user->delete();
    }

    public function testAuthentication(): void
    {
        $response = $this->client->post($this->uri . '/10', [
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer invalid token',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);

        $this->assertEquals(401, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals([
            'message' => 'Unauthenticated.'
        ], $responseBody);
    }

    /** @dataProvider dataproviderUpdateStatus */
    public function testUpdateStatus(string $fromStatus, string $toStatus): void
    {
        $order = $this->createOrder($fromStatus);
        $response = $this->client->post($this->uri . '/' . $order->getId(), [
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'status' => $toStatus
            ]
        ]);

        /** @var Order $modifiedOrder */
        $modifiedOrder = Order::find($order->getId());

        $this->assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals([], $responseBody);
        $this->assertEquals($toStatus, $modifiedOrder->getStatus());

        $order->delete();
    }

    public function dataproviderUpdateStatus(): array
    {
        return [
            'From new to completed' => [
                //fromStatus
                OrderStatusEnum::NEW,
                //toStatus
                OrderStatusEnum::COMPLETED
            ],
            'From completed to new' => [
                //fromStatus
                OrderStatusEnum::COMPLETED,
                //toStatus
                OrderStatusEnum::NEW
            ]
        ];
    }

    private function createOrder(string $status): Order
    {
        $order = (new Order())
            ->setName('John Doe')
            ->setEmail('john.doe@gmail.com')
            ->setDeliveryMode('personal')
            ->setInvoiceName('John Doe Ltd.')
            ->setInvoiceZipCode('4033')
            ->setInvoiceCity('Debrecen')
            ->setInvoiceAddress('Test street 5.')
            ->setDeliveryName('John Doe')
            ->setDeliveryZipCode('4033')
            ->setDeliveryCity('Debrecen')
            ->setDeliveryAddress('Test street 5.')
            ->setStatus($status);

        $order->save();

        return $order;
    }
}
