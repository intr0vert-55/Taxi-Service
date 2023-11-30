@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Ride Info') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <br>
                    <div class="d-flex justify-content-between">
                        <div class="mb3">
                            <p>
                                Name : {{$ride -> userInfo['name']}}
                            </p>
                            <p>
                                Destination : {{$ride ->destination}}
                            </p>
                            <p>
                                Time : {{$ride -> time}}
                            </p>
                        </div>
                        <div class="mb3">
                            <p>
                                Mobile : {{$ride -> userInfo['mobile']}}
                            </p>
                            <p>
                                Distance(Approx.) : {{$ride -> distance}} km
                            </p>
                            @if($ride -> status == "requested")
                                <a href="{{route('driver.takeride',$ride -> id)}}" class = "btn btn-primary">Take ride</a>
                                <a href="{{route('driver.dashboard')}}" class = "btn btn-warning">Go Back</a>
                            @else
                                <p>Fare received : Rs. {{$ride -> fare}}</p>
                                <a href="{{route('driver.rides')}}" class = "btn btn-warning">Go Back</a>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        The route is displayed on the map below
                    </div>
                    {{-- Map --}}
                    <div id='myMap' style='width: 100%; height: 75vh;'></div>
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
        }
    </script>
    <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?key=Ani5lE3wAQ_2W91_pPG9nKoAnc7d3hvvpuZ9YX17U4EFN4IrMxKsyHwjCPcZC2H0&callback=loadMapScenario' async defer></script>
@endpush
