<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="/">
                <img src="{{ asset('metronic/layout2/img/Latvenerogo-logo.png') }}" width="175" height="45"alt="logo" class="logo-default"/>
                </a>
            </div>
            <!-- END LOGO -->
        </div>
        <!-- END HEADER INNER -->
        {{-- BEGIN HEADER OUTTER --}}
        <div class="page-header-outter">
            <div class="page-header-outter-right">
                <ul class="page-header-outter-right">
                @if (Route::has('login'))
                    @if (Auth::check()){{-- pārbaude vai ielogojas un lai laravel kļūdu lapa saprastu ka ielogots --}}
                    <li>
                        <a class="long-links" href="{{ route('profile.index') }}">{{Auth::user()->First_name}} Profils</a>
                    </li>
                    <li>
                        <a href="/">Home</a>
                    </li>
                    <li>
                        <a href="/logout" {{-- izlogošanas iebūvēta funkcionalitāte --}}
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Iziet
                        </a>
                    </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    @else
                    <li>
                        <a href="{{ route('showlogin') }}">Ielogoties</a>
                    </li>
                    <li>
                        <a href="{{ route('showregister') }}">Reģistrēties</a>
                    </li>
                    @endif
                @endif
                </ul>
            </div>
            <div class="page-header-outter-left">
                <ul class="page-header-outter-left">
                @if (Auth::check())
                    @if (Auth::user()->hasRole('Admin')) {{-- tikai admini var skatīti šo --}}
                    <li>
                        <a class="long-links" href="{{ route('showcreate') }}">Izveidot pasākumu</a>
                    </li>
                    <li>
                        <a href="{{ route('showsavedevents',1) }}">Melnraksti</a>
                    </li>
                    @endif
                    <li>
                        <a class="long-links" href="{{ route('reservationusers',1) }}">Manas rezervācijas</a> 
                    </li>   
                @endif
                </ul>
            </div>
        </div>
        {{-- END HEADER OUTTER --}}
    </div>
    <!-- END HEADER -->
    <div class="content-positioner"></div>