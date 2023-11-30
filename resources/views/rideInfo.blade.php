@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>
                        {{ __('Ride : ').$ride -> id }}
                    </h5>
                    <a href="{{route('user.rideslist')}}" class = "btn btn-primary">Go Back</a>
                </div>

                <br>
                <div class="card-body d-flex justify-content-between">
                    <div class = "mb3">
                        <p>
                            Destination : {{$ride -> destination}}
                        </p>
                        <p>
                            Time : {{$ride -> time}}
                        </p>
                        <p>
                            Took by : {{$ride -> driverInfo['name']}}
                        </p>
                    </div>
                    <div class="mb3">
                        <a href="javascript:void(0)" class = "btn btn-primary" data-bs-toggle = "modal" data-bs-target="#exampleModal">Show map <i class="bi bi-geo-alt-fill"></i></a>

                        <br><br>
                        <p>
                            Distance : {{$ride -> distance}}km
                        </p>
                        <p class = "d-inline">
                            Fare : <p style = "color:green">Rs. {{$ride -> fare}}</p>
                        </p>
                        @if($ride -> payment == 0)
                            <a href="javascript:void(0)" class="btn btn-success" data-bs-toggle = "modal" data-bs-target="#payModal">Pay<i class="bi bi-currency-rupee"></i></a>
                        @else
                            <p style = "color:green">Paid <i class="bi bi-check2"></i></p>
                        @endif
                        @if(session('failed'))
                            <p style = "color:red">{{session('failed')}}</p>
                        @endif
                    </div>
                </div>


                {{-- @if($errors)
                    @foreach ($errors as $error)
                        <p>{{$error}}</p>
                    @endforeach
                @endif --}}

                {{-- Review Module --}}

                @if($rating -> isEmpty())
                    <div class="container">
                       <h2>Rate your experience</h2>
                       <form action="{{route('user.review')}}" method = "POST">
                           @csrf

                           <div class="form-group">

                               <h4>
                               <i class="bi bi-star star-1 rate"></i>
                               <i class="bi bi-star star-2 rate"></i>
                               <i class="bi bi-star star-3 rate"></i>
                               <i class="bi bi-star star-4 rate"></i>
                               <i class="bi bi-star star-5 rate"></i>
                               </h4>
                               <input type="text" hidden name = "ride_id" value = {{$ride -> id}}>
                               <input type="text" hidden class = "stars" id = "stars" name = "stars" required>

                           </div>


                           <div class="form-group mb3">
                               <textarea name="review" class = "form-control" id = "review" name = "review" id="" cols="30" rows="3" placeholder="Write about your experience as a review" required></textarea>
                           </div>
                           <br>
                           Submit your rating to continue
                           <br><br>
                           <div class="form-group mb3">
                               <button class = "btn btn-primary" type = "submit">Done</button>
                           </div>
                       </form>
                @else
                    @php
                        foreach($rating as $rate){
                            $rating = $rate;
                            break;
                        }
                    @endphp
                    <div class="container">
                        <h2>Your review</h2>
                        <div class="mb-3">
                            @for($i = 0;$i<5;$i++)
                                @if($i < $rating -> stars)
                                    <i class = "bi bi-star-fill"></i>
                                @else
                                    <i class = "bi bi-star"></i>
                                @endif
                            @endfor
                            <p>
                                Review/Description : {{$rating -> review}}
                            </p>
                            <br>
                            <br>
                            <p>Thank you for travelling with us</p>
                        </div>
                    </div>
                @endif
                </div>
                <br>

                {{-- Map --}}

                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Map</h5>
                        </div>
                        <div class="modal-body">
                            Zoom out the map to get the result properly
                                <div class="card-body">
                                    <div id='myMap' style='width: 100%; height: 75vh;'></div>
                                </div>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Payment <i class="bi bi-currency-rupee"></i></h5>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex flex-column">
                                <div class="para">
                                    The amount you have to pay is
                                </div>
                                <div class="mt-3">
                                    <p style = "color:green;" class = "h1">
                                    Rs. {{$ride -> fare}}
                                    </p>
                                </div>
                                <div class="mt-3">
                                    <form action="{{route('user.pay')}}" method="POST">
                                        @csrf
                                        <input type="text" hidden name="ride_id" id="ride_id" value = {{$ride -> id}} required>
                                        <button class = "btn btn-success"><i class="bi bi-wallet2"></i> Complete Payment</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
@endsection


@push('script')

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    {{-- Bing Maps --}}
    <script type='text/javascript'>
        function loadMapScenario(){
            var map = new Microsoft.Maps.Map(document.getElementById('myMap'), {});
            Microsoft.Maps.loadModule('Microsoft.Maps.Directions', function () {
                var directionsManager = new Microsoft.Maps.Directions.DirectionsManager(map);
                // Set Route Mode to driving
                directionsManager.setRequestOptions({ routeMode: Microsoft.Maps.Directions.RouteMode.driving });
                var waypoint1 = new Microsoft.Maps.Directions.Waypoint({location: new Microsoft.Maps.Location({{$ride -> latitude}}, {{$ride -> longitude}}) });
                var waypoint2 = new Microsoft.Maps.Directions.Waypoint({location: new Microsoft.Maps.Location({{$ride -> destlat}}, {{$ride -> destlong}}) });
                directionsManager.addWaypoint(waypoint1);
                directionsManager.addWaypoint(waypoint2);
                // Set the element in which the itinerary will be rendered
                directionsManager.calculateDirections();
            });
            map.setOptions({
                maxZoom: 12,
            });
        }

        $('.rate').click(function(){
            let star = this.className;
            let starval = star.charAt(16);
            if(star.includes('bi-star-fill'))
                starval = star.charAt(21);
            // console.log(starval);
            document.getElementById('stars').value = starval;
            for(let i = 0;i<5;i++){
                let name = '.star-'+(i+1);
                // console.log(name);
                let fillClass = 'bi bi-star-fill star-'+(i+1);
                let nofillClass = 'bi bi-star star-'+(i+1);
                let starClass = $(name)
                // console.log(starClass.attr('class'));
                let classOfStar = starClass.attr('class');
                if(i<starval){
                    starClass.removeClass(nofillClass);
                    starClass.addClass(fillClass);
                }
                else{
                    // console.log('Inside else '+ classOfStar);
                    if(classOfStar.includes("bi-star-fill")){
                        starClass.removeClass(fillClass);
                        starClass.addClass(nofillClass);
                    }
                }
            }
        });


    </script>
    <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?key=Ani5lE3wAQ_2W91_pPG9nKoAnc7d3hvvpuZ9YX17U4EFN4IrMxKsyHwjCPcZC2H0&callback=loadMapScenario' async defer></script>
@endpush
