<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\socialProfile;


class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'wallet_address', 'about',
        'google_id', 'avatar_url','avatar_name', 'website', 'location', 'reputation_score','page_views',
        'passionate_about','desired_superpower','favorite_crypto',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function messages(){
        return $this->hasMany('App\Messages', 'recipient_id');
    }

    public function reputations(){
        return $this->hasMany('App\Reputations','recipient_id');
    }

    public function log(){
        return $this->hasMany('App\Log','to_user_id');
    }

}
