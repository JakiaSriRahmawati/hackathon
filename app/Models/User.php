<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'profile_picture', 'bio', 'google_id', 'date_of_birth', 'username'   
    ];

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withTimestamps()->withPivot('awarded_at');
    }

    public function friends()
    {
        return $this->hasMany(Friend::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    public function todos()
    {
        return $this->hasMany(Todo::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'creator_id');
    }

    public function campaignParticipations()
    {
        return $this->hasMany(CampaignParticipant::class);
    }

    public function userCampaignTodos()
    {
        return $this->hasMany(UserCampaignTodo::class);
    }



   

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
