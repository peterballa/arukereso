<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        //Note: I dont like mass assignment
        $user = User::create([
            'name' => 'Test Elek',
            'email' => 'test.elek@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        echo "Please copy your authentication token:\n";
        echo $token."\n";
    }
}
