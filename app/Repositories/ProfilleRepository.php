<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfilleRepository implements ProfilleRepositoryInterface
{
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function deleteTokens($user)
    {
        $user->tokens->each(function ($token) {
            $token->delete();
        });
    }

    public function getUserById(int $id)
    {
        return User::find($id); 
    }

    public function updateUser(int $id, array $data)
    {
        $user = User::find($id);

        if ($user) {
            $user->update($data); 
            return $user;
        }

        return null; 
    }
}