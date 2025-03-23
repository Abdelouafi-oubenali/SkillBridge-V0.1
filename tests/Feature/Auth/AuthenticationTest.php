<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $user = \App\Models\User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('api/V1/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);
    $response->assertStatus(200);
});



test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->post('/api/V1/logout'); 
    $response->assertStatus(200)
             ->assertJson(['message' => 'Déconnexion réussie.']);
    $this->assertEmpty($user->tokens);
});
