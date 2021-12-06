<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderCreateTest extends TestCase
{
    private User $user;
    private string $token;

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
    }

    public function tearDown(): void
    {
        $this->user->delete();
    }

    public function testAuthentication(): void
    {
        $client = new Client();
        $response = $client->post(env('APP_URL') . '/api/orders/create', [
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

    public function testSuccess(): void
    {
        $payload =  [
            'name' => 'Test Elek',
            'email' => 'test.elek@gmail.com',
            'deliveryMode' => 'personal',
            'invoiceName' => 'Test Elek Invoice Name',
            'invoiceZipCode' => '4033',
            'invoiceCity' => 'Debrecen',
            'invoiceAddress' => 'Test utca 5',
            'deliveryName' => 'Test Elek Delivery Name',
            'deliveryZipCode' => '4033',
            'deliveryCity' => 'Debrecen',
            'deliveryAddress' => 'Test utca 5',
            'products' => [
                0 => [
                    'name' => 'product 1',
                    'grossPrice' => 420,
                    'quantity' => 22,
                ],
                1 => [
                    'name' => 'product 2',
                    'grossPrice' => 69,
                    'quantity' => 42,
                ],
            ],
        ];

        $client = new Client();
        $response = $client->post(env('APP_URL') . '/api/orders/create', [
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => $payload
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);

        /** @var Order $order */
        $order = Order::find($responseBody['orderId'])
            ->append(['gross_total'])
            ->with(['orderProducts'])
            ->first();

        $this->assertEquals($payload['name'], $order->getName());
        $this->assertCount(count($payload['products']), $order->getOrderProducts());
        $this->assertEquals(12138, $order->getGrossTotalAttribute());
        //TODO another assertions

        $order->delete();
    }
    /**
     * @dataProvider dataproviderInvalidCases
     */
    public function testInvalidCases(array $payload, array $expectedResponse): void
    {
        $client = new Client();
        $response = $client->post(env('APP_URL') . '/api/orders/create', [
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => $payload
        ]);

        $this->assertEquals(422, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertEquals($expectedResponse, $responseBody);

        //This was original
//        $response = $this->postJson('/api/orders/create', $payload, ['Authorization' => 'Bearer ' . $token]);
//
//        $response
//            ->assertStatus($expectedStatus)
//            ->assertJson($expectedResponse);
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function dataproviderInvalidCases(): array
    {
        return [
            'Empty payload' => [
                //payload
                [],
                //expectedResponse
                [
                    'message' => 'The given data was invalid.',
                    'errors' =>
                        [
                            'name' =>
                                [
                                    0 => 'The name field is required.',
                                ],
                            'email' =>
                                [
                                    0 => 'The email field is required.',
                                ],
                            'deliveryMode' =>
                                [
                                    0 => 'The delivery mode field is required.',
                                ],
                            'invoiceName' =>
                                [
                                    0 => 'The invoice name field is required.',
                                ],
                            'invoiceZipCode' =>
                                [
                                    0 => 'The invoice zip code field is required.',
                                ],
                            'invoiceCity' =>
                                [
                                    0 => 'The invoice city field is required.',
                                ],
                            'invoiceAddress' =>
                                [
                                    0 => 'The invoice address field is required.',
                                ],
                            'deliveryName' =>
                                [
                                    0 => 'The delivery name field is required.',
                                ],
                            'deliveryZipCode' =>
                                [
                                    0 => 'The delivery zip code field is required.',
                                ],
                            'deliveryCity' =>
                                [
                                    0 => 'The delivery city field is required.',
                                ],
                            'deliveryAddress' =>
                                [
                                    0 => 'The delivery address field is required.',
                                ],
                        ],
                ]
            ],
            'Only name given' => [
                //payload
                [
                    'name' => 'Test Elek'
                ],
                //expectedResponse
                [
                    'message' => 'The given data was invalid.',
                    'errors' =>
                        [
                            'email' =>
                                [
                                    0 => 'The email field is required.',
                                ],
                            'deliveryMode' =>
                                [
                                    0 => 'The delivery mode field is required.',
                                ],
                            'invoiceName' =>
                                [
                                    0 => 'The invoice name field is required.',
                                ],
                            'invoiceZipCode' =>
                                [
                                    0 => 'The invoice zip code field is required.',
                                ],
                            'invoiceCity' =>
                                [
                                    0 => 'The invoice city field is required.',
                                ],
                            'invoiceAddress' =>
                                [
                                    0 => 'The invoice address field is required.',
                                ],
                            'deliveryName' =>
                                [
                                    0 => 'The delivery name field is required.',
                                ],
                            'deliveryZipCode' =>
                                [
                                    0 => 'The delivery zip code field is required.',
                                ],
                            'deliveryCity' =>
                                [
                                    0 => 'The delivery city field is required.',
                                ],
                            'deliveryAddress' =>
                                [
                                    0 => 'The delivery address field is required.',
                                ],
                        ],
                ]
            ]
        ];
    }
}
