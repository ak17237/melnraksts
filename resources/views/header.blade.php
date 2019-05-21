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
                        <a class="long-links name" href="{{ route('profile.index') }}">{{Auth::user()->First_name}} Profils</a>
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
                <li class="searchbox" @if(!Auth::check() || Auth::check() && Auth::user()->hasRole('User')) style="float:right;" @endif>
                    <form action="{{ route('searchget') }}" class="searchboxform @if(!Auth::check() || Auth::check() && Auth::user()->hasRole('User')) searchboxformguest @endif" method="POST">
                            {{csrf_field()}}
                    <input type="text" name="search" class="form-control searchinput">
                    <button type="submit" class="searchbtn">
                    <img src="/svg/magnifier-tool.svg" alt="Search icon" width="25" height="25">
                    </button>
                    </form>
                    <a class="searchbtn-small" href="{{ route('search',['options' => 'checkevent>>off>off>off>off','page' => '1']) }}">
                        <img src="/svg/magnifier-tool.svg" alt="Search icon" width="25" height="25">
                    </a>
                </li>
                </ul>
            </div>
        </div>
        {{-- END HEADER OUTTER --}}
    </div>
    <!-- END HEADER -->
    <div class="content-positioner"></div>