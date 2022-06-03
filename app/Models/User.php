<?php

namespace App\Models;

use App\Models\Checkin;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use hasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nif',
        'social_sec_num',
        'hours_on_contract',
        'is_admin'
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

    // User has many checkins
    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public function scopeYear(Builder $query, $year)
    {
        return $query->whereYear('created_at', $year);
    }

    public function scopeMonth(Builder $query, $month)
    {
        return $query->whereMonth('created_at', $month);
    }

    public function scopeDay(Builder $query, $day)
    {
        return $query->whereDay('created_at', $day);
    }

    public function scopeName(Builder $query, $user)
    {
        return $query->where('name', 'Like', '%' . $user . '%');
    }

    public function scopeEmail(Builder $query, $email)
    {
        return $query->where('email',  $email);
    }

    public function sendPasswordResetNotification($token)
    {
        $route = route('password.reset', $token, true);
        $formattedRoute = Str::remove(['api/', 'api.'], $route);
        $this->notify(new ResetPassword($formattedRoute));
    }
}
