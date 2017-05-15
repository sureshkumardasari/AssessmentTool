<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Assessment Tool</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
 <!--  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
	<!--  <link href="{{ asset('/css/app.css') }}" rel="stylesheet"> -->
 
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

/*.title {
	font-size: 40px;
	margin-bottom: 40px;
	margin-top: 40px;
}

.quote {
	font-size: 14px;
	font-style: italic;
}*/
.footer {
    background: #8d8d8d none repeat scroll 0 0;
    bottom: 0;
    /*display: table-row;*/
    height: auto;
    left: 0;
    padding: 10px 0 6px;
    position: fixed;
    text-align: center;
    width: 100%;
}
.fade-carousel {
    position: relative;
    height: 90vh;
}
.fade-carousel .carousel-inner .item {
    height: 90vh;
}
.fade-carousel .carousel-indicators > li {
    margin: 0 2px;
    background-color: #f39c12;
    border-color: #f39c12;
    opacity: .7;
}
.fade-carousel .carousel-indicators > li.active {
  width: 10px;
  height: 10px;
  opacity: 1;
}

/********************************/
/*          Hero Headers        */
/********************************/
.hero {
    position: absolute;
    top: 60%;
    left: 83%;
    z-index: 3;
    color: #fff;
    text-align: center;
    text-transform: uppercase;
    text-shadow: 1px 1px 0 rgba(0,0,0,.50);
      -webkit-transform: translate3d(-100%,-100%,0);
         -moz-transform: translate3d(-100%,-100%,0);
          -ms-transform: translate3d(-100%,-100%,0);
           -o-transform: translate3d(-100%,-100%,0);
              transform: translate3d(-100%,-100%,0);
}
.hero h1 {
    font-size: 6em;    
   /* font-weight: bold;*/
    margin: 0;
    padding: 0;
}

.fade-carousel .carousel-inner .item .hero {
    opacity: 0;
    -webkit-transition: 2s all ease-in-out .1s !important;
       -moz-transition: 2s all ease-in-out .1s !important; 
        -ms-transition: 2s all ease-in-out .1s !important; 
         -o-transition: 2s all ease-in-out .1s !important; 
            transition: 2s all ease-in-out .1s !important; 
}
.fade-carousel .carousel-inner .item.active .hero {
    opacity: 1;
    -webkit-transition: 2s all ease-in-out .1s !important;
       -moz-transition: 2s all ease-in-out .1s !important; 
        -ms-transition: 2s all ease-in-out .1s !important; 
         -o-transition: 2s all ease-in-out .1s !important; 
            transition: 2s all ease-in-out .1s !important;    
}

/********************************/
/*            Overlay           */
/********************************/
.overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 2;
    background-color: #080d15;
    opacity: .6;
}

/********************************/
/*          Custom Buttons      */
/********************************/
.btn.btn-lg {padding: 10px 40px;}
.btn.btn-hero,
.btn.btn-hero:hover,
.btn.btn-hero:focus {
    color: #f5f5f5;
    background-color: #1abc9c;
    border-color: #1abc9c;
    outline: none;
    margin: 20px auto;
}

/********************************/
/*       Slides backgrounds     */
/********************************/
.fade-carousel .slides .slide-1, 
.fade-carousel .slides .slide-2,
.fade-carousel .slides .slide-3 {
  height: 100vh;
  background-size: cover;
  background-position: center center;
  background-repeat: no-repeat;
}
.fade-carousel .slides .slide-1 {
  background-image: url("images/image3.jpg"); 
}
.fade-carousel .slides .slide-2 {
  background-image: url("images/banner.jpg");
}
.fade-carousel .slides .slide-3 {
  background-image: url("images/banner (1).jpg");
}

/********************************/
/*          Media Queries       */
/********************************/
@media screen and (min-width: 980px){
    .hero { width: 980px; }    
}
@media screen and (max-width: 640px){
    .hero h1 { font-size: 4em; }    
}
.navbar {
    position: relative;
    min-height: 67px;
    margin-bottom: 0px;
    border: 1px solid transparent;
}
</style>
</head>
<body>
	@section('top-nav')
        @include('top-nav')
    @show


<div class="">
	<!-- <div class="content">
		<div class="title">Welcome <br>to<br> Assessment Tool</div>
		<div class="quote">{{ Inspiring::quote() }}</div>
	</div> -->
		<div class="carousel fade-carousel slide" data-ride="carousel" data-interval="4000" id="bs-carousel">
  <!-- Overlay -->
  

  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#bs-carousel" data-slide-to="0" class="active"></li>
    <li data-target="#bs-carousel" data-slide-to="1"></li>
    <li data-target="#bs-carousel" data-slide-to="2"></li>
  </ol>
  
  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item slides active">
        <div class="overlay"></div>
      <div class="slide-1"></div>
      <div class="hero">
        <hgroup>
            <h1>Smart Kidstar</h1>        
            <h3>Play,Explore and Learn</h3>
        </hgroup>
        <a href="{{ url('/auth/login') }}" class="btn btn-hero btn-lg" role="button">Login</a>
      </div>
    </div>
    <div class="item slides">
        <div class="overlay"></div>
      <div class="slide-2"></div>
      <div class="hero">        
        <hgroup>
            <h1>Young Talents</h1>        
            <h3>Inspiration,Innovation and Discover</h3>
        </hgroup>       
        <a href="{{ url('/auth/login') }}" class="btn btn-hero btn-lg" role="button">Login</a>
      </div>
    </div>
    <div class="item slides">
        <div class="overlay"></div>
      <div class="slide-3"></div>
      <div class="hero">        
        <hgroup>
            <h1>Dream World</h1>        
            <h3>Work,Acheive and Succeed</h3>
        </hgroup>
        <a href="{{ url('/auth/login') }}" class="btn btn-hero btn-lg" role="button">Login</a>
      </div>
    </div>
  </div> 
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


