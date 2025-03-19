<?php
namespace App\Repositories;

interface ProfilleRepositoryInterface
{
    public function findByEmail(string $email);
    public function deleteTokens($user);
    public function getUserById(int $id); 
    public function updateUser(int $id, array $data);
}