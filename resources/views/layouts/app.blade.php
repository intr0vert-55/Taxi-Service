<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ config('app.name', 'Laravel') }}</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  {{-- <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon"> --}}

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
</head>

<body class="toggle-sidebar">

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">

      <i class="bi bi-list toggle-sidebar-btn"></i>
      @if(str_contains($_SERVER['REQUEST_URI'], 'driver'))
        <a class="navbar-brand" href="{{ url('/driver/dashboard') }}">
      @else
        <a class="navbar-brand" href="{{ url('/') }}">
      @endif
        {{ config('app.name', 'Laravel') }}
      </a>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">


            @guest
                {{-- @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                @endif

                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif --}}
                    <li class="nav-item m-3">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('User') }}</a>
                    </li>
                    <li class="nav-item m-3">
                        <a class="nav-link" href="{{ route('driver.login-view') }}">{{ __('Driver') }}</a>
                    </li>
            @else
                <li class="nav-item dropdown m-3">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
          {{-- <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            {{ Auth::user()->name }}
          </a>

          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form> --}}
        </div>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->


     <!-- Sidebar -->
  <aside id="sidebar" class="sidebar">
    {{-- {{$_SERVER['REQUEST_URI']}} --}}

    @if(str_contains($_SERVER['REQUEST_URI'], 'driver'))

        @if($_SERVER['REQUEST_URI'] != '/driver')
            <h5>How the rides going, {{Auth::user() -> name}}?</h5>
            <ul class="sidebar-nav" id="sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link " href="{{route('driver.rides')}}">
                      <i class="bi bi-car-front-fill"></i>
                      <span>Rides</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="{{route('driver.profile')}}">
                      <i class="bi bi-person-gear"></i>
                      <span>Profile</span>
                    </a>
                </li>
            </ul>
        @else
            <h5>Welcome to Taxi Service. Login to continue</h5>
        @endif


    @elseif(str_contains($_SERVER['REQUEST_URI'], 'user') || str_contains($_SERVER['REQUEST_URI'], 'home'))

        <h5>Hello {{Auth::user() -> name}}, how can we help you?</h5>

        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link " href="javascript:void(0)" id = "request" data-bs-toggle = "modal" data-bs-target="#exampleModal">
                    <i class="bi bi-car-front-fill"></i>
                    <span>Request a ride</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="{{route('user.rideslist')}}">
                    <i class="bi bi-clock-history"></i>
                    <span>Rides list</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="{{route('user.profile')}}">
                    <i class="bi bi-person-circle"></i>
                    <span>Profile</span>
                </a>
            </li>
        </ul>



    @else
        <h5>Welcome to Taxi Service. Login to continue</h5>
    @endif
    {{-- <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link " href="#">
              <i class="bi bi-person-gear"></i>
              <span>Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="#">
              <i class="bi bi-person-fill"></i>
              <span>Clients</span>
            </a>
        </li>
    </ul> --}}
  </aside>

  <!-- End of sidebar -->
    <main id = "main" class = "main">
        @yield('content')
    </main>




  <!-- Vendor JS Files -->
  <script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{asset('assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{asset('assets/vendor/quill/quill.min.js') }}"></script>
  <script src="{{asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{asset('assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('assets/js/main.js')}}"></script>

  @stack('script')

</body>

</html>
