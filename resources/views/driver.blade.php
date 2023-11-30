@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Hello Driver!<br><br>
                    <div class="alert alert-info d-flex justify-content-between">
                        <p>
                            @if($location['status'] == 1)
                                Your location is displayed below
                            @else
                                Your location cannot be determined. You might not get any rides
                            @endif
                        </p>
                        <a href="javascript:void(0)" class = "btn btn-info" data-bs-toggle = "modal" data-bs-target="#exampleModal">Rides</a>
                    </div>

                    {{-- All the rides --}}

                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Requests</h5>
                            </div>
                            <div class="modal-body">
                                @isset($rides)
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>User Name</th>
                                                <th>Destination</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rides as $ride)
                                            <tr>
                                                <td>{{$ride -> userInfo['name']}}</td>
                                                <td>{{$ride -> destination}}</td>
                                                <td>{{$ride -> time}}</td>
                                                @if($ride -> status == 'requested')
                                                    <td>
                                                        <a href="{{route('driver.ride',$ride -> id)}}">More Details <i class="bi bi-info-circle"></i></a>
                                                    </td>
                                                @elseif($ride -> status == "accepted")
                                                    <td>
                                                        <div class="alert alert-primary">Accepted</div>
                                                    </td>
                                                @else
                                                    <td>
                                                        <div class="alert alert-success">Completed</div>
                                                    </td>
                                                @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>No requests</p>
                                @endisset
                                <a href="javascript:void(0)" data-bs-dismiss="modal" class="btn btn-primary" id = "back">Close</a>
                            </div>
                          </div>
                        </div>
                    </div>

                    {{-- location in maps --}}

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
            Microsoft.Maps.loadModule('Microsoft.Maps.Search', function () {
                var searchManager = new Microsoft.Maps.Search.SearchManager(map);
                var reverseGeocodeRequestOptions = {
                    location: new Microsoft.Maps.Location('{{$location["latitude"]}}', '{{$location["longitude"]}}'),
                    callback: function (answer, userData) {
                        map.setView({ bounds: answer.bestView });
                        map.entities.push(new Microsoft.Maps.Pushpin(reverseGeocodeRequestOptions.location));
                        document.getElementById('printoutPanel').innerHTML =
                            answer.address.formattedAddress;
                    }
                };
                searchManager.reverseGeocode(reverseGeocodeRequestOptions);
            });
        }


        // function loadMapScenario(){
        //     var map = new Microsoft.Maps.Map(document.getElementById('myMap'), {
        //         /* No need to set credentials if already passed in URL */
        //         center: new Microsoft.Maps.Location(47.606209, -122.332071),
        //         zoom: 12
        //     });
        //     Microsoft.Maps.loadModule('Microsoft.Maps.Directions', function () {
        //         var directionsManager = new Microsoft.Maps.Directions.DirectionsManager(map);
        //         directionsManager.setRenderOptions({ itineraryContainer: document.getElementById('printoutPanel') });
        //         directionsManager.showInputPanel('directionsInputContainer');
        //         let dist = $('.drTitleRight').val();
        //         console.log(dist);
        //     });
        // }
    </script>
    <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?key=Ani5lE3wAQ_2W91_pPG9nKoAnc7d3hvvpuZ9YX17U4EFN4IrMxKsyHwjCPcZC2H0&callback=loadMapScenario' async defer></script>
@endpush
