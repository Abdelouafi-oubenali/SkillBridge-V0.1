<?php
use App\Models\User;

describe("CourseController tests", function () {

    test('can see list of courses', function () {
        $user = \App\Models\User::factory()->create(); 
    
        $this->actingAs($user); 
    
        $course = \App\Models\Course::factory()->create([
            'users_id' => $user->id, 
        ]);

        $response = $this->get('api/V1/courses'); 
    
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'content',
                'category_id',
            ],
        ]);
    });
    

    test('can create a course', function () {
        $user = User::factory()->create(); 
        $category = \App\Models\Category::factory()->create();
    
        $course = [
            'title' => 'css tailwind',
            'content' => 'Course content',
            'category_id' => $category->id,
        ];
    
        $response = $this->actingAs($user)->post('/api/V1/courses', $course);
    
        $response->assertStatus(201);
        $response->assertJson([
            'title' => 'css tailwind',
            'category_id' => $category->id,
        ]);
    });
    test('can update a course when user is the owner', function () {
        $user = User::factory()->create(); // إنشاء مستخدم
        $category = \App\Models\Category::factory()->create();
        $course = \App\Models\Course::factory()->create([
            'category_id' => $category->id,
            'users_id' => $user->id, // الدورة تنتمي لهذا المستخدم
        ]);
    
        $updateData = [
            'title' => 'Updated Course Title',
            'content' => 'Updated content',
            'category_id' => $category->id,
        ];
    
        // تسجيل الدخول باستخدام نفس المستخدم الذي يملك الدورة
        $response = $this->actingAs($user)->put("api/V1/courses/{$course->id}", $updateData);
    
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Course updated successfully',
            'course' => [
                'title' => 'Updated Course Title',
            ],
        ]);
        $this->assertDatabaseHas('courses', ['title' => 'Updated Course Title']);
    });
    

    test('can delete a course', function () {
        $course = \App\Models\Course::factory()->create();
    
        $response = $this->delete("api/V1/courses/{$course->id}");
    
        $response->assertStatus(204);
    
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    });

    test('cannot update a non-existent course', function () {
        $update = [
            'title' => 'Updated Nonexistent Course',
        ];

        $response = $this->put('api/v1/Courses/9999', $update);

        $response->assertStatus(404);
    });

    test('cannot delete a non-existent course', function () {
        $response = $this->delete('api/v1/Courses/9999');

        $response->assertStatus(404);
    });
});