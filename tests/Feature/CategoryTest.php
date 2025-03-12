<?php

use Illuminate\Support\Facades\DB;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test("can list categories" ,function (){
    $response = $this->get("api/V1/categories");
    $response->assertStatus(200);
    $response->assertJsonStructure([
        '*' => [
            'name',
        ]
        ]);
});

test("can create a categories", function (){
   $data = [
       'name' => 'youcoude0',
   ];

   $response = $this->postJson('/api/V1/categories',$data);
   $response->assertStatus(201);
   $this->assertDatabaseHas('categories',$data);
});


test("can get a specific categoriy", function () {
     DB::table('categories')->insert([
        'id' => 1,
        'name' => 'youcode',
        'created_at' => now(),
        'updated_at' => now(),
     ]);

     $response = $this->getJson("/api/V1/categories/1");

    $response->assertStatus(200);
    $response->assertJson([
        'id' => 1,
        'name' => 'youcode',
    ]);
});

test("can update a tag", function() {
    DB::table('categories')->insert([
        'id' => 1,
        'name' => 'youcode',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $data = [
        'name' => 'youssofia',
    ];

    $response = $this->putJson("/api/V1/categories/1", $data);
    $response->assertStatus(200);

    $this->assertDatabaseHas('categories', [
        'id' => 1,
        'name' => 'youssofia',
    ]);
});


test('can delete a tag',function () {
     DB::table('categories')->insert([
        'id' => 1,
        'name' => 'youcode',
        'created_at' => now(),
        'updated_at' => now(),
     ]);
     $response = $this->deleteJson('/api/V1/categories/1');
     $response->assertStatus(204);
     $this->assertDatabaseMissing('categories',[
        'id' => 1,
     ]);
});

