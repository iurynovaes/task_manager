<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    protected $fillable = [
        'title', 
        'description', 
        'status', 
        'user_id', 
        'building_id', 
        'created_by', 
        'updated_by'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    
    public function createComment(string $message): Comment
    {
        $userId = Auth::id();

        $comment = $this->comments()->create([
            'message' => $message,
            'user_id' => $userId,
        ]);

        return $comment;
    }
    
    public function canChangeStatus(): bool
    {
        return $this->status !== TaskStatus::COMPLETED;
    }
}