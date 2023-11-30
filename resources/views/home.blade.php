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

                    {{ __('Welcome!') }}

                    <br><br>
                    <div class="alert alert-info d-flex justify-content-between">
                    <p>
                        @if($location['status'] == 1)
                                Your location is displayed below
                        @else
                            Your location cannot be determined. The driver will contact you
                        @endif
                    </p>
                    <a href = "javascript:void(0)" class = "btn btn-info" id = "ride_request" data-bs-toggle = "modal" data-bs-target="#exampleModal">Request a ride</a>
                    </div>

                    {{-- Map --}}

                    <div id='myMap' style='width: 100%; height: 75vh;'></div>

                    {{-- AJAX Call for requesting a ride --}}

                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Ride Request</h5>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <input type="hidden" name="_token" id = "csrf" value="{{ csrf_token() }}" />
                                    <div class="card-body">
                                        <div class="form-group">
                                            {{-- <label for="destination">Destination</label> --}}
                                            {{-- <input type="text" class = "form-control mt-2" name="destination" id="printoutPanel" required placeholder="Enter a location that is available in Bing Maps"> --}}
                                            <div id='searchBoxContainer'>
                                                <label for="destination">Destination</label>
                                                <input type= 'text' name = "destination" class = "form-control destination" id= 'searchBox' required/>
                                            </div>
                                            <div id = "printoutPanel"></div>
                                            <input type="text" id = "longitude" hidden name="latitude">
                                            <input type="text" id = "latitude" hidden name="longitude">
                                        </div>
                                        <div class="cs-form">
                                            <label for="time">Time</label>
                                            <input type="time" class="form-control mt-2" name="time" id="time">
                                          </div>
                                        <div class="form-group mt-4">
                                            <a href="javascript:void(0)" data-bs-dismiss="modal" class="btn btn-primary" id = "back">Cancel</a>
                                            <a href = "javascript:void(0)" class = "btn btn-primary" id = "send">Request</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                          </div>
                        </div>
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

            //Map

            var map = new Microsoft.Maps.Map(document.getElementById('myMap'), {});
            Microsoft.Maps.loadModule('Microsoft.Maps.Search', function () {
                var searchManager = new Microsoft.Maps.Search.SearchManager(map);
                var reverseGeocodeRequestOptions = {
                    location: new Microsoft.Maps.Location('{{$location["latitude"]}}', '{{$location["longitude"]}}'),
                    callback: function (answer, userData) {
                        map.setView({ bounds: answer.bestView });
                        map.entities.push(new Microsoft.Maps.Pushpin(reverseGeocodeRequestOptions.location));
                        // document.getElementById('printoutPanel').innerHTML = answer.address.formattedAddress;
                    }
                };
                searchManager.reverseGeocode(reverseGeocodeRequestOptions);
            });

            //Location module

            Microsoft.Maps.loadModule('Microsoft.Maps.AutoSuggest', {
                    callback: onLoad,
                    errorCallback: onError
                });
                function onLoad() {
                    var options = { maxResults: 5 };
                    var manager = new Microsoft.Maps.AutosuggestManager(options);
                    manager.attachAutosuggest('#searchBox', '#searchBoxContainer', selectedSuggestion);
                }
                function onError(message) {
                    document.getElementById('printoutPanel').innerHTML = message;
                }
                function selectedSuggestion(suggestionResult) {
                    'Suggestion: ' + suggestionResult.formattedSuggestion +
                    '<br> Lat: ' + suggestionResult.location.latitude +
                    '<br> Lon: ' + suggestionResult.location.longitude;
                    document.getElementById('latitude').value = suggestionResult.location.latitude;
                    document.getElementById('longitude').value = suggestionResult.location.longitude;
                }

        }



        // AJAX

        function requestRide(){
            console.log('In the request');
            $('#send').click(function(){
                var dest = $('.destination').val();
                var time = $('#time').val();
                if(dest === ""){
                    alert('Enter a destination');
                    return;
                }
                console.log('Destination verfied');
                const date = new Date();
                let currentTime = date.toLocaleTimeString([],{hour: '2-digit',minute: '2-digit',hour12: false});
                if(time < currentTime){
                    alert('Enter a time that is either equal to or greater than the current time');
                    return;
                }
                console.log('Time verfied');
                var token = $('#csrf').val();


                var latitude = $('#latitude').val();
                var longitude = $('#longitude').val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    data: {
                        dest : dest,
                        time : time,
                        latitude : latitude,
                        longitude : longitude,
                        _token: token
                    },
                    url: "{{ url('user/ride') }}",
                    success: function(response) {
                        $('#exampleModal').modal('hide');
                        var message = response.message;
                        alert(message);
                    }
                });
            });
        }

        $(document).ready(function(){
            console.log('Document Ready');
            $("#ride_request").click(function(){
                requestRide();
            });
            $("#request").click(function(){
                requestRide();
            });
        });
    </script>
    <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?key=Ani5lE3wAQ_2W91_pPG9nKoAnc7d3hvvpuZ9YX17U4EFN4IrMxKsyHwjCPcZC2H0&callback=loadMapScenario' async defer></script>
@endpush
