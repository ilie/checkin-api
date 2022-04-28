<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'checkin_date', 'checkin_time', 'checkout_time'];

    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function scopeUser(Builder $query, $user)
    {
        return $query->where('user_id', 'Like', '%' . $user . '%');
    }

    public function scopeDate(Builder $query, $date)
    {
        return $query->where('checkin_date', 'Like', '%' . $date . '%');
    }
}
