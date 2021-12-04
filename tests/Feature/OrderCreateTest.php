<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class OrderCreateTest extends TestCase
{
    //TODO setup create user and token

    //TODO assert authentication
    //TODO assert some validation case
    //TODO assert transaction
    //TODO assert success order creation

    public function testEmptyPayload(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = $this->postJson('/api/orders', [], ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(422);

        $user->delete();
    }
}
