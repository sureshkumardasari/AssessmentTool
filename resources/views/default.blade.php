<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Assessment Tool</title>
	<link href="{{ asset('/css/style.css') }}" rel='stylesheet' type='text/css'>
	<!-- <link href="{{ asset('/css/common.css') }}" rel='stylesheet' type='text/css'> -->
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href="{{ asset('/css/fonts.googleapis.css') }}" rel='stylesheet' type='text/css'>

    
    <link href="{{ asset('/css/dataTables.bootstrap.min.css') }}" rel='stylesheet' type='text/css'>
    @section('style')
        @include('style')
    @show
    @section('header-assets')
	<script src="{{ asset('/js/jquery.min.js') }}"></script>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	@show
</head>
<body>
	@section('top-nav')
        @include('top-nav')
    @show

	<!-- breadcrumb -->	
	@if (Auth::guest())
	@else
	<div class="container">
		<div class="row">
		    @section('breadcrumb')
                {{ breadcrumb() }}
                <div class="clr"></div>
            @show
		</div>
	</div>
	@endif
	{{--<section class="main-area">
		<div class="breadcrumb">
			@section('breadcrumb')
				{{ breadcrumb() }}
				<div class="clr"></div>
			@show
		</div>

		@yield('content')

	</section>--}}
	<!-- breadcrumb -->
	<div class="middle_content_top">
	@yield('content')
	</div>
	
	@section('footer')
        @include('footer')
    @show

	@section('scripts')
        @include('scripts')
    @show

</body>
</html>
