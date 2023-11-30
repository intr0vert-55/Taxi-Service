<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stevebauman\Location\Facades\Location;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Driver;
use App\Models\Ride;
use App\Models\User;
use App\Models\Rating;
use App\Jobs\RideAcceptedJob;

class DriverController extends Controller
{

    public function location(Request $request){
        $latitude = 0;
        $longitude = 0;
        $status = 0;
        $ip = $request -> ip();
        // $ip = '2400:65c0:6:159:908:62ad:9bbf:12b3';
        if($location = Location::get($ip)){
            $latitude = $location -> latitude;
            $longitude = $location -> longitude;
            $status = 1;
        }
        $location = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'status' => $status,
        ];
        return $location;
    }
    public function index(Request $request){
        $location = $this -> location($request);
        $latitude = $location['latitude'];
        $longitude = $location['longitude'];
        $driver = Auth::user() -> id;
        DB::update("Update drivers set latitude = $latitude, longitude = $longitude where id = $driver");
        $rides = $this -> rides($driver);
        return view('driver', compact('location','rides'));
    }

    public function rides($id){
        $rides = Ride::with('userInfo')
        ->orderBy('created_at','desc')
        ->where('driver_id',$id)
        ->get();
        return $rides;
    }

    public function rideDetails($id){
        $ride = Ride::with('userInfo')
                        ->find($id);
        $distance = round($this -> distanceBetween($ride -> latitude, $ride -> longitude, $ride -> destlat, $ride ->destlong));
        $ride -> distance = $distance;
        $fare = 150;
        if($ride -> distance > 15){
            $fare = $ride -> distance * 10;
        }
        DB::update("Update rides Set fare = $fare, distance = $distance where id = $id");
        // echo $ride;
        return view('rideDetails',compact('ride'));
    }

    public function distanceBetween($lat1,$lon1,$lat2,$lon2 ){
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else{
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $distance = $dist * 60 * 1.1515 * 1.609344;
            return $distance;
        }
    }

    public function takeRide($id){
        DB::update("Update rides set status = 'accepted' where id = $id");
        $ride = Ride::with('userInfo', 'driverInfo')->find($id);
        dispatch(new RideAcceptedJob($ride));
        return redirect() -> route('driver.dashboard');
    }

    public function allRides(){
        $id = Auth::user() -> id;
        $rides = Ride::where('driver_id', $id) -> get();
        return view('ridesList',compact('rides'));
    }

    public function profile(){
        $id = Auth::user() -> id;
        $ratings = Rating::with('userInfo', 'rideInfo')
                    -> where('driver_id', $id)
                    -> orderBy('created_at','desc')
                    -> get();
        return view('profile',compact('ratings'));
    }
    public function edit(Request $request){
        $driver = Driver::find(Auth::user()->id) -> id;
        // echo $driver;
        $request -> validate([
            'name' => 'required',
            'email' =>'required|email|unique:drivers,email,'.$driver,
            'mobile' => 'required|min:10|numeric'
        ]);
        // echo $request;
        DB::update("Update drivers set name = '$request->name', email = '$request->email', mobile = '$request->mobile' where id = $driver");
        echo "<script>alert('Profile Updated successfully')</script>";
        return redirect()->route('driver.profile')->with('message','Profile is updated successfully');
    }
}
