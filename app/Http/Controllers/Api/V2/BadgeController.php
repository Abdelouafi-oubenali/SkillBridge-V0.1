<?php
namespace App\Http\Controllers\Api\V2;

use App\Models\Badge;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBadgeRequest;
use App\Http\Requests\UpdateBadgeRequest;
use Illuminate\Contracts\Support\Responsable;

class BadgeController extends Controller
{

    public function index()
    {
        return response()->json(Badge::all(), 200);
    }

    public function store(StoreBadgeRequest $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }
            if ($user->role != 'admin') {
            return response()->json(['error' => 'Vous n\'avez pas la permission d\'effectuer cette action.'], 403);
        }
        $badge = Badge::create($request->validated());
        return response()->json($badge, 201);
    }

    public function update(UpdateBadgeRequest $request, $id)
    {
        $user = $request->user();
        if(!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        if($user->role != 'admin') 
        { 
            return response()->json(['error' => 'Vous n\'avez pas la permission d\'effectuer cette action.'], 403);
        }
        $badge = Badge::findOrFail($id);
        $badge->update($request->validated());
        return response()->json($badge, 200);
    }

    public function destroy(Request $request , $id)
    {
        $user = $request->user();
        if(!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        if($user->role != 'admin') 
        { 
            return response()->json(['error' => 'Vous n\'avez pas la permission d\'effectuer cette action.'], 403);
        }
        $badge = Badge::findOrFail($id);
        $badge->delete();
        return response()->json(['message' => 'Badge supprimé avec succès'], 200);
    }

    public function badgeToMentors(Request $request) {

        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }
        $userCourses = Course::where('users_id', $user->id)->get();
        $totalEnrollments = 0;
        foreach ($userCourses as $course) {
            $totalEnrollments ++;
        }
        if($totalEnrollments >= 5) {
            
        }
    }
    
}
