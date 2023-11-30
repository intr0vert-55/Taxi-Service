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
                    <a href="{{route('home')}}" class = "btn btn-primary">Go Back</a>
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
                            <a href="{{route('user.editProfile')}}" class = "mx-5 btn btn-outline-primary">
                                Edit profile
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </h4>
                        <h5>
                            Phone Number : {{Auth::user() -> mobile}}
                        </h5>
                        <h5>
                            Email : {{Auth::user() -> email}}
                        </h5>
                        <h5>
                            No. of rides taken : {{$count}}
                        </h5>
                        <h5>
                            Distance travelled with us : {{$distance}} km
                        </h5>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
