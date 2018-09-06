<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>{{ env('APP_NAME') }} - @yield('title')</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    @yield('meta')

    <!-- Bootstrap core CSS     -->
     <!--<link href="/css/bootstrap.min.css" rel="stylesheet" /> -->

    <!-- Animation library for notifications   -->
    <!-- <link href="/css/animate.min.css" rel="stylesheet"/> -->

    <!--  Light Bootstrap Table core CSS    -->
    <link href="//{{ env('APP_URI') . mix('/css/app.css')}}" rel="stylesheet"/>

    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
     <!--<link href="/css/pe-icon-7-stroke.css" rel="stylesheet" />-->
    
    <!-- Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
                'rootDomain' => env('APP_URI'),
            ]); ?>
    </script>
</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-color="purple">

    <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="{{ route('home') }}" class="simple-text">
                    {{ env('APP_NAME') }}
                </a>
            </div>

            <ul class="nav">
                <li class="active">
                    <a href="{{route('home')}}/">
                        <i class="pe-7s-graph"></i>
                        <p>Join a Group</p>
                    </a>
                </li>
                @ifgroup(cah)
                <li class="active">
                    <a href="{{route('cahcards')}}/">
                        <i class="pe-7s-graph"></i>
                        <p>CAH Cards</p>
                    </a>
                </li>
                @endif
                @can('admin.roles')
                <li class="active">
                    <a href="{{route('admin.roles')}}">
                        <i class="pe-7s-graph"></i>
                        <p>Manage Roles</p>
                    </a>
                </li>
                @endcan
                @can('admin.logs')
                <li>
                    <a href="{{route('log-viewer::dashboard')}}">
                        <i class="pe-7s-graph"></i>
                        <p>System Logs</p>
                    </a>
                </li>
                @endcan
                @can('mod.isAMod')
                <li class="active">
                    <a href="{{route('moderator.index')}}">
                        <i class="pe-7s-smile"></i>
                        <p>Moderators</p>
                    </a>
                </li>
                <li class="active">
                    <a href="{{route('moderator.flagnotice.index')}}">
                        <i class="pe-7s-smile"></i>
                        <p>Flag Notices</p>
                    </a>
                </li>
                <li class="active">
                    <a href="{{route('moderator.ratings.viewuser', 'self')}}">
                        <i class="pe-7s-id"></i>
                        <p>Own Ratings</p>
                    </a>
                </li>
		        <li class="active">
                    <a href="https://edu.fjme.me">
                        <i class="pe-7s-notebook"></i>
                        <p>FJEducation</p>
                    </a>
                </li>
                @endcan
            </ul>
    	</div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">@yield('title')</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-dashboard"></i>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li>
                           <a href="/logout">
                                @if(Auth::check())
                                    {{Auth::user()->nickname}}
                                @else
                                    Anonymous
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    @if(env('FJ_API_ENABLED') == false)
                    <div class="alert alert-danger">
                        <button type="button" aria-hidden="true" class="close">×</button>
                        <span><b> Warning - </b> FunnyJunk API is currently not working. Synching permissions will not work, awaiting fix from admin.</span>
                    </div>
                    @endif
                    @yield('content')
                </div>
            </div>
        </div>


        <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">
                    <ul>
                        <li>
                            <a href="#">
                                @yield('title')
                            </a>
                        </li>

                    </ul>
                </nav>
                <p class="copyright pull-right">
                   <a href="https://posttwo.pt">Posttwo</a>, Yeet yourgoodfriend
                </p>
            </div>
        </footer>

    </div>
</div>


</body>

    <!--   Core JS Files   -->
    <script src="//{{ env('APP_URI') . mix('/js/app.js') }}" type="text/javascript"></script>
    <script src="//{{ env('APP_URI') . mix('/js/app-admin.js') }}" type="text/javascript"></script>
</html>
