<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    /** @use HasFactory<\Database\Factories\BadgeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',  
    ];


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_badges')->withTimestamps();
    }
}
