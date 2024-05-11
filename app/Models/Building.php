<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    protected $fillable = [
        'name',
        'manager', 
        'address'
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}