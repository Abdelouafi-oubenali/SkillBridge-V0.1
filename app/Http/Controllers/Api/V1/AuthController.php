<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|max:255'
        ]);

        $user = $this->userRepository->findByEmail($request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Les informations d\'identification fournies sont incorrectes.'
            ], 401);
        }

        $token = $user->createToken('Auth-Token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie.',
            'user' => $user,
            'token_type' => 'Bearer',
            'token' => $token
        ], 200);
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $this->userRepository->create($request->all());

        if ($user) {
            $token = $user->createToken('Auth-Token')->plainTextToken;

            return response()->json([
                'message' => 'Inscription réussie.',
                'user' => $user,
                'token_type' => 'Bearer',
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'message' => 'Erreur lors de l\'inscription.'
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        if ($request->user()) {
            $this->userRepository->deleteTokens($request->user());

            return response()->json([
                'message' => 'Déconnexion réussie.'
            ], 200);
        }

        return response()->json([
            'message' => 'Utilisateur non authentifié.'
        ], 401);
    }
}