@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>
                        {{ __('Profile') }}
                    </h3>
                    <a href="{{route('driver.dashboard')}}" class = "btn btn-primary">Go Back</a>
                </div>

                <div class="card-body">


                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif


                    <div class="mb3">
                        <h4>
                            Name : {{Auth::user() -> name}}
                            <a href="{{route('driver.editProfile')}}" class = "mx-5 btn btn-outline-primary">
                                Edit profile
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </h4>
                        <h5>
                            Phone Number : {{Auth::user() -> mobile}}
                        </h5>
                        <h5>
                            No. of rides completed : {{count($ratings)}}
                        </h5>
                        @php
                            $fare = 0;
                            $distance = 0;
                            $stars = 0;
                            $count = 0;
                            foreach($ratings as $rating){
                                $fare += $rating -> rideInfo['fare'];
                                $distance += $rating -> rideInfo['distance'];
                                $stars += $rating -> stars;
                                $count++;
                            }
                            $stars /= $count;
                            $stars = round($stars);
                        @endphp
                        <h5>
                            Total distance travelled : {{$distance}} km
                        </h5>
                        <h5>
                            Total fare : Rs. {{$fare}}
                        </h5>
                        <h5>
                            Rating :
                            @for($i = 0;$i < 5;$i++)
                                @if($i < $stars)
                                    <i class = "bi bi-star-fill"></i>
                                @else
                                    <i class = "bi bi-star"></i>
                                @endif
                            @endfor
                        </h5>
                        <br><br>
                        <h5>
                            Recent Reviews
                        </h5>
                        <ul class = "list-group list-group-flush">
                            @foreach($ratings as $key => $rating)
                                @if($key < 5)
                                    <li class = "list-group-item">
                                        <h4>{{$rating -> userInfo['name']}}</h4>
                                    </li>
                                    <li class = "list-group-item">
                                        <small>
                                            Rating :
                                            @for($i = 0;$i < 5;$i++)
                                                @if($i < $rating -> stars)
                                                    <i class = "bi bi-star-fill"></i>
                                                @else
                                                    <i class = "bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </small>
                                    </li>
                                    <li class = "list-group-item">
                                        <small>
                                            Review/Description : {{$rating -> review}}
                                        </small>
                                    </li>
                                @else
                                    @break
                                @endif
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
