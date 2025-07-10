<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'specialist',
        'email',
        'mobile',
        'user_id',
        'password',
        'email_verified_at',
        'mobile_verified_at',
        'country',
        'country_code',
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
        'mobile_verified_at' => 'datetime',
    ];

    /**
     * Automatically hash the password when setting it.
     */
    public function setPasswordAttribute($password)
  {
    if (!empty($password)) {
        // لو مش متحدد بالفعل ومش معمول له hash
        if (!Hash::needsRehash($password)) {
            $this->attributes['password'] = $password;
        } else {
            $this->attributes['password'] = Hash::make($password);
        }
    }
  }

    /**
     * Accessor for full name (optional).
     */
    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function favorites()
{
    return $this->belongsToMany(Article::class, 'favorites')->withTimestamps();
}


}