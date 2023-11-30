<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Driver;

class Ride extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'destination',
        'destlat',
        'destlong',
        'time',
        'driver_id',
        'status'
    ];

    public function userInfo(){
        return $this -> hasOne(User::class, 'id', 'user_id');
    }
    public function driverInfo(){
        return $this -> hasOne(Driver::class, 'id', 'driver_id');
    }
}
