<?php 
namespace App\Http\Controllers\Api\V1;

use App\Models\Course;
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
        // dd($data);
    
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
}














