<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'stars',
        'review',
        'user_id',
        'driver_id',
        'ride_id',
    ];
    public function userInfo(){
        return $this -> hasOne(User::class, 'id', 'user_id');
    }
    public function rideInfo(){
        return $this -> hasOne(Ride::class, 'id', 'ride_id');
    }
}
