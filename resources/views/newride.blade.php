<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Ride</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">You got a new ride</div>

                    <div class="card-body">
                        <div class="mb3">
                            <h4>
                                Name : {{$ride->userInfo->name}}
                            </h4>
                            <h6>
                                Destination : {{$ride -> destination}}
                            </h6>
                            <h6>
                                Phone Number : {{$ride->userInfo->mobile}}
                            </h6>
                            <h5>
                                To get more details and to take the ride
                                <a href="{{route('driver.ride', $ride->id)}}">
                                    click here
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
