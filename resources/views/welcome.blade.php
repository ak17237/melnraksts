<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> <!--<![endif]-->

    <head>
            
        <meta charset="utf-8"/>

        <title>Pasākumu sistēma</title>

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.0/css/bootstrap.min.css" 
        integrity="sha384-PDle/QlgIONtM1aqA2Qemk5gPOE7wFq8+Em+G/hmo5Iq0CCmYZLv3fVRDJ4MMwEA" crossorigin="anonymous">
        

        <!-- Globāli obligāti stili sākums -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/> {{-- Fonts --}}
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous"> {{-- Ikonas --}}
        <link href="{{ asset('metronic/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('metronic/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('metronic/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css"/>
        <!-- Globāli obligāti stili beigas -->

        <!-- Tēmu stili sākums -->
        <link href="{{ asset('metronic/global/css/components-rounded.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('metronic/global/css/plugins.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('metronic/layout2/css/layout.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('metronic/layout2/css/themes/blue.css') }}" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="{{ asset('metronic/layout2/css/custom.css') }}" rel="stylesheet" type="text/css"/>
        <!-- Tēmu stili beigas -->
        
        <link rel="shortcut icon" href="favicon.ico"/>

        
        <link href="{{ asset('css/bootstrapoverstyle.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/jquery.bxslider.css') }}">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/hover-min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/page-404.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/loader.css') }}" rel="stylesheet" type="text/css">
            <script src="{{ asset('metronic/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('js/loader.js') }}"></script> {{-- lai pirms lapas sastāvs ielādējas,loader jau varētu strādāt --}}

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
        

    </head>
    <body>
        @include('header')
        <div class="content-page">   
            @yield('content')
        </div>
    </div>
    
        @include('footer')

        <script src="{{ asset('js/jquery.visible.js') }}" type="text/javascript"></script>
        <script src="{{ asset('metronic/global/plugins/jquery-migrate.min.js')}}" type="text/javascript"></script>
        <!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
        <script src="{{ asset('metronic/global/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/jquery.bxslider.js') }}"></script>
        <script src="{{ asset('js/inputactivation.js') }}"></script>
        <script src="{{ asset('js/numbercount.js') }}"></script>
        <script src="{{ asset('js/slidercontrol.js') }}"></script>
        <script src="{{ asset('js/reservationslidercontrol.js') }}"></script>
        <script src="{{ asset('js/alertcontrol.js') }}"></script>
        <script src="{{ asset('js/reservateinput.js') }}"></script>
        <script src="{{ asset('js/scroll.js') }}"></script>
        <script src="{{ asset('js/historyslidercontrol.js') }}"></script>
        <script src="{{ asset('js/jquery.form.js') }}"></script>
        <script src="{{ asset('js/gallery.js') }}"></script>
        <script src="{{ asset('js/profile.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" 
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.0/js/bootstrap.min.js" 
        integrity="sha384-7aThvCh9TypR7fIc2HV4O/nFMVCBwyIUKL8XCtKE+8xgCgl/PQGuFsvShjr74PBp" crossorigin="anonymous"></script>
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{ asset('metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('metronic/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('metronic/global/plugins/jquery.cokie.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{ asset('metronic/global/plugins/jquery.pulsate.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('metronic/global/plugins/jquery.sparkline.min.js') }}" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="{{ asset('metronic/global/scripts/app.js') }}" type="text/javascript"></script>
        <script src="{{ asset('metronic/layout2/scripts/layout.js') }}" type="text/javascript"></script>
        <script src="{{ asset('metronic/layout2/scripts/quick-sidebar.js') }}" type="text/javascript"></script>
        <script src="{{ asset('metronic/global/pages/scripts/tasks.js') }}" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <script>
        jQuery(document).ready(function() {    
        App.init(); // init metronic core componets
        Layout.init(); // init layout
        QuickSidebar.init() // init quick sidebar

        });
        </script>
        <script></script>
    </body>
</html>
