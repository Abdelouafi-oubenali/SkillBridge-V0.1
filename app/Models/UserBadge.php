<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserBadge extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'badge_id'
    ];

    protected $table = 'users_badges';
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
