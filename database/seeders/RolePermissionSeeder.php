<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    // Créer les rôles
    $admin = Role::create(['name' => 'admin']);
    $enseignant = Role::create(['name' => 'enseignant']);
    $etudiant = Role::create(['name' => 'étudiant']);

    // Créer les permissions
    $createPost = Permission::create(['name' => 'create-post']);
    $editPost = Permission::create(['name' => 'edit-post']);
    $deletePost = Permission::create(['name' => 'delete-post']);

    $admin->permissions()->attach([$createPost->id, $editPost->id, $deletePost->id]);
    $enseignant->permissions()->attach([$createPost->id, $editPost->id]);
    $etudiant->permissions()->attach([$createPost->id]);
}
}
