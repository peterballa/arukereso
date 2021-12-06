<?php

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderQueryTest extends TestCase
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
        $this->uri = env('APP_URL') . '/api/orders/query';
        $this->client = new Client();
    }

    public function tearDown(): void
    {
        $this->user->delete();
    }

    public function testAuthentication(): void
    {
        $response = $this->client->post($this->uri, [
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
}
