<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
// TODO 7.2 test_email_can_be_verified
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        // TODO 13: test_user_create_task
        // fix this by adding a parameter
        return $this->hasMany(Task::class,"id");
    }

    public function comments()
    {
        // TODO 14.1: test_show_users_comments
        // add the code here for two-level relationship     
        return $this->hasManyThrough(Comment::class,Task::class,'users_id',"task_id");
    }

    public function projects(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Project::class)->withPivot('start_date');
    }
}
