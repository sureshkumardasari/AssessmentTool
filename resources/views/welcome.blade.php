<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Assessment Tool</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href="{{ asset('/css/fonts.googleapis.css') }}" rel='stylesheet' type='text/css'>
	@section('style')
        @include('style')
    @show
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<script src="{{ asset('/js/jquery.min.js') }}"></script>
</head>
<body>
	@section('top-nav')
        @include('top-nav')
    @show

<style>
.container {
	text-align: center;
	vertical-align: middle;
}

.content {
	text-align: center;
	display: inline-block;
	color: #666666;
}

.title {
	font-size: 40px;
	margin-bottom: 40px;
	margin-top: 40px;
}

.quote {
	font-size: 14px;
	font-style: italic;
}
</style>
<div class="container">
	<div class="content">
		<div class="title">Welcome <br>to<br> Assessment Tool</div>
		<div class="quote">{{ Inspiring::quote() }}</div>
	</div>
</div>


    @section('footer')
        @include('footer')
    @show

    @section('scripts')
        @include('scripts')
    @show
	
</body>
</html>


