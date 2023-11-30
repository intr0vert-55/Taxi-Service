<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stevebauman\Location\Facades\Location;

use App\Models\User;
use App\Models\Driver;
use App\Models\Ride;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Jobs\NewRideJob;

class UserController extends Controller
{

    // public function __construct(){
    //     $this -> middleware('auth');
    // }

    public function location(Request $request){
        $latitude = 0;
        $longitude = 0;
        $status = 0;
        $ip = $request -> ip();
        // return $ip;
        // $ip = '59.91.157.95';
        if($location = Location::get($ip)){
            // return dd($location);
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
        // return $location;
        return view('home', compact('location'));
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


    public function ride(Request $request){
        echo "<script>console.log('Inside the function')</script>";
        $user_id = Auth::user() -> id;
        $rides = Ride::select('status')
                        ->where('user_id', $user_id)
                        ->get();
        foreach($rides as $ride){
            if($ride -> status == 'requested'){
                $response = [
                    'status' => 'ok',
                    'success' => false,
                    'message' => 'You already requested for a ride. You cannot request for another until that ride is completed'
                ];
                return $response;
            }
        }
        $location = $this -> location($request);
        $drivers = Driver::select('id', 'latitude', 'longitude')
                            ->get();
        $minDistance = 300000;      //random value
        $driver_id = 0;             //null id
        foreach($drivers as $driver){
            $distance = $this -> distanceBetween($driver -> latitude, $driver -> longitude, $location['latitude'], $location['longitude']);
            if($distance < $minDistance){
                $minDistance = $distance;
                $driver_id = $driver -> id;
            }
        }
        // $driver_id = 1;
        $ride = [
            'user_id' => $user_id,
            'latitude' => $location['latitude'],
            'longitude' => $location['longitude'],
            'destination' => $request -> dest,
            'time' => $request -> time,
            'destlat' => $request -> latitude,
            'destlong' => $request -> longitude,
            'driver_id' => $driver_id,
            'status' => 'requested',
        ];
        // echo "<script>console.log('Ride created')</script>";
        $data = Ride::create($ride);
        if($data){
            $response = [
                'status' => 'ok',
                'success' => true,
                'message' => 'Request sent. You will receive a response soon'
            ];
        }
        else{
            $response = [
                'status' => 'ok',
                'success' => false,
                'message' => 'Something went wrong. Please try again'
            ];
        }
        $ride = Ride::with('userInfo', 'driverInfo')->find($ride -> id);
        dispatch(new NewRideJob($ride));
        return $response;
    }

    public function ridesList(){
        $id = Auth::user() -> id;
        $rides = Ride::with('userInfo')
        ->orderBy('created_at','desc')
        ->where('user_id',$id)
        ->get();
        return view('ridesList', compact('rides'));
    }
    public function rideInfo($id){
        $ride = Ride::with('driverInfo')
                ->find($id);
        // echo $ride;
        $rating = Rating::where('ride_id',$id) -> get();
        if(Auth::user()->id != $ride -> user_id){
            echo "<script>alert('You're not allowed to access this data')</script>";
            return redirect() -> route('home');
        }
        else{
            return view('rideInfo',compact('ride','rating'));
        }
    }

    public function review(Request $request){
        $request -> validate([
            'ride_id' => 'required',
            'stars' => 'required| min : 1 | max : 5',
            'review' => 'required'
        ]);
        $ride_id = $request -> ride_id;
        $ride = Ride::findorfail($ride_id);
        if($ride -> payment == 0){
            $payUp = "You need to pay the fare first";
            return redirect() -> route('user.rideinfo', $ride_id) -> with('failed', $payUp);
        }
        // echo $ride;
        $rating = [
            'stars' => $request -> stars,
            'review' => $request -> review,
            'user_id' => $ride -> user_id,
            'driver_id' => $ride -> driver_id,
            'ride_id' => $ride_id,
        ];
        Rating::create($rating);
        DB::update("Update rides set status = 'completed' where id = $ride_id");
        return redirect() -> route('home');
    }

    public function pay(Request $request){
        $ride = $request -> ride_id;
        DB::update("Update rides set payment = '1' where id = $ride");
        return redirect() -> route('user.rideinfo', $ride);
    }

    public function profile(){
        $rides = Ride::with('userInfo')->where('user_id', Auth::user()->id)->get();
        $count = 0;
        $distance = 0;
        foreach($rides as $ride){
            $count++;
            $distance += $ride -> distance;
        }
        return view('userprofile', ['count' => $count, 'distance' => $distance]);
    }

    public function edit(Request $request){
        $user = User::find(Auth::user()->id) -> id;
        $request -> validate([
            'name' => 'required',
            'email' =>'required|email|unique:users,email,'.$user,
            'mobile' => 'required|min:10|numeric'
        ]);
        // echo $request;
        DB::update("Update users set name = '$request->name', email = '$request->email', mobile = '$request->mobile' where id = $user");
        echo "<script>alert('Profile Updated successfully')</script>";
        return redirect()->route('user.profile')->with('message','Profile is updated successfully');
    }
}
