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
        
        if ($user->role !== 'mentor') {
            return response()->json([
                'error' => 'Vous n’êtes pas autorisé à créer un cours. Veuillez utiliser un compte de mentor.'
            ], 403);
        }
    
        $data = $request->all();
        $data['users_id'] = $user->id;
        // dd($data);
    
        return $this->courseRepository->create($data);
    }

    public function get_mes_course()
    {
        $user = auth()->user();
    
        if ($user->role === 'mentor') {
            $mesCourses = Course::where('users_id', $user->id)->get();
            return response()->json(['courses' => $mesCourses], 200);
        }
    
        return response()->json(['error' => 'Vous n\'êtes pas autorisé à voir ces cours.'], 403);
    }
    
    

    public function update(StoreCourseRequest $request, Course $course)
    {
        $user = auth()->user();
        if ($user->role === 'admin' || $course->users_id === $user->id) {
            $course->update($request->validated());
            return response()->json(['message' => 'Cours mis à jour avec succès', 'course' => $course], 200);
        }
        return response()->json(['error' => 'Vous n\'êtes pas autorisé à modifier ce cours.'], 403);
    }
    
    
    public function destroy(Course $course)
    {
        $user = auth()->user();
    
        if ($user->role === 'admin' || $course->users_id === $user->id) {
            $this->courseRepository->delete($course->id);
            return response()->json(['message' => 'Cours supprimé avec succès'], 200);
        }
    
        return response()->json(['error' => 'Vous n\'êtes pas autorisé à supprimer ce cours.'], 403);
    }


    public function addVideo(Request $request, $courseId)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = auth()->user();
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json(['error' => 'Cours non trouvé'], 404);
        }

        if ($user->role !== 'admin' && $course->users_id !== $user->id) {
            return response()->json(['error' => 'Vous n\'avez pas la permission d\'ajouter une vidéo.'], 403);
        }

        $validated = $request->validate([
            'video' => 'required|file|mimes:mp4,mov,avi|max:102400', 
        ]);

        $path = $request->file('video')->store('videos', 'public');

        $course->update(['video' => $path]);

        return response()->json([
            'message' => 'Vidéo ajoutée avec succès',
            'course' => $course
        ], 201);
    }

    
}














