<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    const COLUMN_ID = 'id';
    const TABLE = 'users';

    protected $table = self::TABLE;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $rules = [
        'firstname' => 'required|max:255',
        'lastname' => 'required|max:255',
        'email' => 'required|string|email|max:255|unique:users',
    ];

    public function bookings(){
        return $this->hasMany('App\Models\Booking');
    }
}
