<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Course;
use App\Models\UserBadge;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    /**
     * Affiche le formulaire de création d'un cours.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (Gate::denies('create-course')) {
            abort(403, "Vous n'avez pas la permission de créer un cours.");
        }
        return view('courses.create');
    }

    /**
     * Permet à un étudiant de s'inscrire à un cours.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Course $course
     * @return \Illuminate\Http\JsonResponse
     */

    //  function de calculle totale de student enrollement par ùentore 

     private function TotalStudentEnrolle ($user_id){
        $mesCourses = Course::where('users_id', $user_id)->get();

        $totalEnrollments = 0;

        foreach ($mesCourses as $course) {
            $totalEnrollments += Enrollment::where('course_id', $course->id)->count();
        }
        return $totalEnrollments;
    }

    // total de course enroole par student 

    private function TotalMesCourseEnrolllmenStudent ($user)
    {
        $mesCourse = Enrollment::where('user_id', $user); 
        return $mesCourse->count();
    }

    public function enroll(Request $request, Course $course)
    {
        $user = $request->user();
        
       
        if (!Gate::allows('enroll-course', $user)) {
            Log::error('Accès refusé : L\'utilisateur n\'a pas le rôle "student".', ['user' => $user]);
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à vous inscrire.'], 403);
        }
       
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
    
        if ($existingEnrollment) {
            Log::warning('L\'utilisateur est déjà inscrit à ce cours.', [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);
            return response()->json(['message' => 'Vous êtes déjà inscrit à ce cours.'], 409);
        }
        

        // Création de l'inscription
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'pending',
        ]);
         
        $TotalzCorse = $this->TotalStudentEnrolle($course->users_id);

        // assagn badg a student apee enrollment a 5 course 

         if($user->role == 'student'){
            $toutaleMesCOurse = $this->TotalMesCourseEnrolllmenStudent($user->id);
            if($toutaleMesCOurse == 10) {
                UserBadge::create([
                    'user_id' => $user->id,
                    'badge_id' => 3
                ]);
            }
         }

        //  assign badg a mintours apree creation des 5 course 
        if($TotalzCorse === 5) {
            UserBadge::create([
                'user_id' => $user->id,
                'badge_id' => 2
            ]);
        }


        Log::info('Nouvelle inscription créée : ', ['enrollment' => $enrollment]);
    
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $course->title,
                    ],
                    'unit_amount' => $course->price * 100, 
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/api/V2/checkout/success?enrollment_id=' . $enrollment->id),
            'cancel_url' => url('/api/V2/checkout/cancel'),
        ]);

        return response()->json([
            'message' => 'Inscription réussie. Redirection vers le paiement.',
            'payment_url' => $session->url,
        ], 201);
    }
    

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Enrollment $enrollment
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        $user = $request->user();

        if (!Gate::allows('manage-enrollment', $user)) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à gérer cette inscription.'], 403);
        }
    
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:accepted,rejected',
        ]);
    
        if ($validator->fails()) 
        {
            return response()->json([
                'message' => 'Validation échouée.',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        $enrollment->update(['status' => $request->status]);
    
        return response()->json([
            'message' => 'Statut de l\'inscription mis à jour.',
            'enrollment' => $enrollment,
        ], 200);
    }
    

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin') 
        {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à voir toutes les inscriptions.'], 403);
        }

        $enrollments = Enrollment::with(['user', 'course'])->get();

        return response()->json(['enrollments' => $enrollments], 200);
    }
}