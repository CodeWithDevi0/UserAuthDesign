<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'experience',
        'title',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function addExperience()
    {
        $this->experience += 1;
        $maxExp = config('user-levels.exp_per_level', 10);

        // Check if should level up
        if ($this->experience >= $maxExp && $this->level < config('user-levels.max_level', 5)) {
            $this->levelUp();
        }

        $this->save();
    }

    private function levelUp()
    {
        $this->level += 1;
        $this->experience = 0;
        $this->updateTitleAndAvatar();
    }

    private function updateTitleAndAvatar()
    {
        $titles = config('user-levels.titles');
        $this->title = $titles[$this->level]['name'] ?? $titles[config('user-levels.max_level')]['name'];
        
        // Get avatar based on level
        $this->avatar = config('avatars.level_avatars.' . $this->level, config('avatars.default'));
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getLevelInfo()
    {
        $titles = config('user-levels.titles');
        $currentTitle = $titles[$this->level] ?? $titles[1];
        
        return [
            'level' => $this->level,
            'title' => $currentTitle['name'],
            'description' => $currentTitle['description'],
            'experience' => $this->experience,
            'max_experience' => config('user-levels.exp_per_level'),
            'avatar' => $this->avatar,
            'progress_percentage' => ($this->experience / config('user-levels.exp_per_level')) * 100
        ];
    }
}
