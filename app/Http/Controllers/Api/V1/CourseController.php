<?php 
namespace App\Http\Controllers\Api\V1;

use App\Models\Course;
use App\Models\UserBadge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Repositories\CourseRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourseController extends Controller
{
    use AuthorizesRequests; 


    protected $courseRepository;

 

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    private function TotalCoursesCreated($user) {

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }
        $userCourses = Course::where('users_id', $user->id)->get();
        $totalEnrollments = 0;
        foreach ($userCourses as $course) {
            $totalEnrollments ++;
        }
        return $totalEnrollments;
    }

    public function index()
    {
        return $this->courseRepository->all();
    }

    public function show($id)
    {
        return $this->courseRepository->find($id);
    }

    public function store(StoreCourseRequest $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);  
        }
        $user = auth()->user();
        $data = $request->all();
        $data['users_id'] = $user->id;
        $TotaleCourse = $this->TotalCoursesCreated($user);
        if($TotaleCourse === 5) {
            UserBadge::create([
                'user_id' => $user->id,
                'badge_id' => 1
            ]);
        }   
        return $this->courseRepository->create($data);
    }

    public function update(StoreCourseRequest $request, Course $course)
    {
        $this->authorize('update', $course);
        $course->update($request->all());
        return response()->json(['message' => 'Course updated successfully', 'course' => $course]);
    }

    public function destroy($id)
    {
        return $this->courseRepository->delete($id);
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        
        $courses = Course::query()
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', '%'.$query.'%')
                  ->orWhere('description', 'like', '%'.$query.'%');
            })
            ->get();
        return response()->json($courses);
    }

    public function getAllCoursesParCategories($categoryId = null)
    {
        if ($categoryId) {
            return Course::where('category_id', $categoryId)->get();
        }
        return Course::all();
    }
    
}














