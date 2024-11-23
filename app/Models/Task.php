<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'completed', 
        'user_id',
        'is_favorite',
        'is_deleted',
        'deleted_at'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'is_favorite' => 'boolean',
        'is_deleted' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 