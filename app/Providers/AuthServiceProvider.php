<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Policies\UserPolicy;
use App\Policies\CoursePolicy;
use App\Policies\EnrollmentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Les politiques de modèle à enregistrer.
     *
     * @var array
     */
    protected $policies = [
        Course::class => CoursePolicy::class,
        User::class => UserPolicy::class,
        Enrollment::class => EnrollmentPolicy::class,
    ];

    /**
     * Enregistre les services de l'application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Définit les portes (Gates) pour les autorisations.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('update-profile', function (User $user, User $profileUser) {
            return $user->id === $profileUser->id;
        });

        Gate::define('create-course', function (User $user) {
            return $user->role === 'teacher' || $user->role === 'admin';
        });

        Gate::define('edit-course', function (User $user, Course $course) {
            return $user->id === $course->user_id || $user->role === 'admin';
        });

        Gate::define('enroll-course', function (User $user) {
            return trim(strtolower($user->role)) === 'student';
        });

        Gate::define('manage-enrollment', function (User $user) {
            return $user->role === 'mentor' || $user->role === 'admin';
        });
    }
}