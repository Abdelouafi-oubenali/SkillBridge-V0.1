<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Tag;
use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatistiquesController extends Controller
{
    public function getCourseStats()
    {
        $totalCourses = Course::count();

        $coursesWithEnrollments = Course::withCount('enrollments')->get();

        $totalEnrollments = Enrollment::count();


        return response()->json([
            'total_courses' => $totalCourses,
            'courses_with_enrollments' => $coursesWithEnrollments,
            'total_enrollments' => $totalEnrollments
        ]);
    }

   public function getcategoryeStats () 
   {
      $totalCategorys = Category::count(); 
      return response()->json([
        'total des categorises' => $totalCategorys
      ]);
   }

   public function gettagseStats () 
   {
      $totalTags = Tag::count(); 
      return response()->json([
        'total des categorises' => $totalTags
      ]);
   }
}