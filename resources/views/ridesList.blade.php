@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Rides List') }}</div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Destination</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Info</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rides as $ride)
                                <tr>
                                    <td>{{$ride -> id}}</td>
                                    <td>{{$ride -> destination}}</td>
                                    <td>{{$ride -> time}}</td>
                                    <td class = "w-25 h-25">
                                        {{$ride -> status}}
                                    </td>
                                    <td>
                                        @if(str_contains($_SERVER['REQUEST_URI'], 'driver'))
                                            <a href="{{route('driver.ride',$ride -> id)}}">Info <i class="bi bi-info-circle"></i></a>
                                        @else
                                            <a href="{{route('user.rideinfo',$ride->id)}}">Info <i class="bi bi-info-circle"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
@endsection
