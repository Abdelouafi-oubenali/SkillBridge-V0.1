<?php

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});



use App\Models\Tag;
use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Testing\RefreshDatabase;

// uses(RefreshDatabase::class);

test("can list tags", function () {
    $response = $this->get("api/V1/tags");

    $response->assertStatus(200);
    
    $response->assertJsonStructure([
        '*' => [  
            'name',
        ],
    ]);
});

test('can create a tag', function () {
    $data = [
        'name' => 'Laravel',
    ];

    $response = $this->postJson('/api/V1/tags', $data);

    $response->assertStatus(201);

    $this->assertDatabaseHas('tags', $data);
});

test('can get a specific tag', function () {
    DB::table('tags')->insert([
        'id' => 1,
        'name' => 'Laravel',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $response = $this->getJson("/api/V1/tags/1");
    $response->assertStatus(200);
    $response->assertJson([
        'id' => 1,
        'name' => 'Laravel',
    ]);
});


test('can update a tag', function () {
    DB::table('tags')->insert([
        'id' => 1,
        'name' => 'kizaru Name',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $data = [
        'name' => 'Laravel',  
    ];

    $response = $this->putJson("/api/V1/tags/1", $data);
    $response->assertStatus(200);

    $this->assertDatabaseHas('tags', [
        'id' => 1,  
        'name' => 'Laravel',  
    ]);
});


test('can delete a tag', function () {
    DB::table('tags')->insert([
        'id' => 1,
        'name' => 'Old Name',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->deleteJson("/api/V1/tags/1");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('tags', [
        'id' => 1,  
    ]);
});
