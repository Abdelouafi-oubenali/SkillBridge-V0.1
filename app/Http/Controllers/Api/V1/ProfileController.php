<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use App\Repositories\ProfilleRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    protected $userRepository;

    public function __construct(ProfilleRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * Get the authenticated user's profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non authentifié.'
            ], 401);
        }

        return response()->json([
            'message' => 'Profil utilisateur récupéré avec succès.',
            'user' => $user
        ], 200);

    }

    /**
     * Update the authenticated user's profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
 public function updateProfile(Request $request): JsonResponse
{
    try {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non authentifié.'
            ], 401);
        }

        if (Gate::denies('updateProfile', $user)) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à modifier ce profil.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'profile_picture' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation échouée',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('name')) {
            $user->name = $request->input('name');
        }
        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return response()->json([
            'message' => 'Profil utilisateur mis à jour avec succès.',
            'user' => $user
        ], 200);

    } catch (\Exception $e) {
        Log::error('Erreur lors de la mise à jour du profil utilisateur : ' . $e->getMessage());

        return response()->json([
            'message' => 'Une erreur interne est survenue. Veuillez réessayer plus tard.'
        ], 500);
    }
}
    
}
