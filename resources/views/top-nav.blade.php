	<nav class="navbar navbar-default" id="hbc">
		<div class="container-fluid" >
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- <a class="navbar-brand" href="#">Laravel</a> -->
				<?php
						$rolename = getRole();

					  	$branding = (array)getbranding();
					  	if(is_array($branding) && count($branding) && $branding['filepath']!="")
  						{
  							$logopath = asset('/data/brandingimages/'.$branding['filepath']);
  						}
  						else
  						{
  							$logopath = asset('/images/logo.png');
  						}
				?>
		                    <a class="navbar-brand"><img class="logo-img1 col-md-12" src="{{ $logopath }}" ></a>
			</div>
<?php 
$session=Session::get('starttestT');
//dd($session);
?>
@if($rolename != "student")
			<div class="collapse navbar-collapse" id="top-navbar-collapse-1">
				@if (Auth::guest())
				<!-- <ul class="nav navbar-nav">
					<li><a href="{{ url('/') }}">Home</a></li>
					<li><a href="{{ url('/') }}">About Us</a></li>
					<li><a href="{{ url('/') }}">Licensing</a></li>
					<li><a href="{{ url('/') }}">Contact Us</a></li>
				</ul> -->
				@else
				<ul class="nav navbar-nav">
					
					<li><a href="{{ url('/') }}">Home</a></li>
				
					@if($rolename != "student")
						<li><a href="{{url('proctor_dashboard')}}">Proctor DashBoard</a></li>
					@endif
				
					<li class="dropdown">
			          <a href=" " class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Resources <span class="caret"></span></a>
			          <ul class="dropdown-menu">
			          	<li><a href="{{ url('/resources/category') }}">Categories</a></li>
			            <li><a href="{{ url('/resources/subject') }}">Subjects</a></li>
			            <li><a href="{{ url('/resources/lesson') }}">Lessons</a></li>
			            <li role="separator" class="divider"></li>
			            <li><a href="{{ url('/resources/question') }}">Questions</a></li>
			            <li><a href="{{ url('/resources/passage') }}">Passages</a></li>
			            <li role="separator" class="divider"></li>
			            <li><a href="{{ url('/resources/assessment') }}">Assessments</a></li>
			            <li><a href="{{ url('/resources/assignment') }}">Assignments</a></li>
			          </ul>
			        </li>
			        <li><a href="{{ url('/grading') }}">Grading</a></li>
					<li><a href="{{ url('/report') }}">Reports</a></li>
				
					@if($rolename == 'admin' || $rolename == 'administrator')
			        <li class="dropdown">
			          <a href=" " class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administration <span class="caret"></span></a>
			          <ul class="dropdown-menu"> 
                       <li><a href="{{ url('/user/brandings') }}">Branding</a></li>
			          	<li role="separator" class="divider"></li>
			            <li><a href="{{ url('/user') }}">Users</a></li>
			            <li role="separator" class="divider"></li>

						@if($rolename == 'administrator')
			            <li><a href="{{ url('/user/institution') }}">Institutions</a></li>	          
			            <!-- <li><a href="{{ url('/user/role') }}">Roles</a></li> -->
			            @endif
			          </ul>
			        </li>
			        @endif
				</ul>
				@endif
				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<!-- <li><a href="{{ url('/auth/login') }}">Login</a></li> -->
						<!-- <li><a href="{{ url('/auth/register') }}">Register</a></li> -->
					@else
					<?php
					if(Auth::user()->profile_picture != NULL)
					{
						if(getenv('s3storage'))
						{
							$profile_picture = getS3ViewUrl(Auth::user()->profile_picture, 'user_profile_pic_128');
						}
						else
						{
							$profile_picture = asset('/data/uploaded_images/128x128/'.Auth::user()->profile_picture);
						}			
					}
					else
					{
						$profile_picture = asset('/images/profile_pic.jpg');	
					}
					?>
					<!-- <li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><img width="50" height="50" alt="{{ Auth::user()->name }}" src="{{ $profile_picture }}" class="img-circle"> {{ ucwords(Auth::user()->name) }} <span class="caret"></span></a> -->
                    <li class="dropdown">
						<a href="#" class="dropdown-toggle textname" data-toggle="dropdown" role="button" aria-expanded="false" title="{{ ucwords(Auth::user()->name) }}" data-toggle="tooltip"><img width="35" height="35" alt="{{ Auth::user()->name }}" src="{{ $profile_picture }}" class="img-circle"> 
						{{ ucwords(Auth::user()->name) }} <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
					
						<li><a href="{{ url('/user/profile') }}">Profile</a></li>
						
							<li role="separator" class="divider"></li>
							<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
						</ul>
					</li>
					@endif
				</ul>
			</div>
			@elseif($rolename == "student")
					<div class="collapse navbar-collapse" id="top-navbar-collapse-1">
				@if (Auth::guest())
				<!-- <ul class="nav navbar-nav">
					<li><a href="{{ url('/') }}">Home</a></li>
					<li><a href="{{ url('/') }}">About Us</a></li>
					<li><a href="{{ url('/') }}">Licensing</a></li>
					<li><a href="{{ url('/') }}">Contact Us</a></li>
				</ul> -->
				@else
				<ul class="nav navbar-nav">
					@if($session == 1 )
					<input type="hidden" id="alert" name="alert" value="">
					<li><a href="javascript:void(0);" onclick="getAlert(); return false;">Home</a></li>
				@elseif($session == 0)
				<li><a href="{{ url('/') }}">Home</a></li>
				@endif	
					
				
					@if($session == 1)
					<input type="hidden" id="alert" name="alert" value="">
					<li><a href="javascript:void(0);" onclick="getAlert(); return false;">My Assignments</a></li>
					@elseif($session == 0)
					<li><a href="{{ url('/assessment/myassignment') }}">My Assignments</a></li>
 				
 					@endif
				</ul>
				@endif
				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						
					@else
					<?php
					if(Auth::user()->profile_picture != NULL)
					{
						if(getenv('s3storage'))
						{
							$profile_picture = getS3ViewUrl(Auth::user()->profile_picture, 'user_profile_pic_128');
						}
						else
						{
							$profile_picture = asset('/data/uploaded_images/128x128/'.Auth::user()->profile_picture);
						}			
					}
					else
					{
						$profile_picture = asset('/images/profile_pic.jpg');	
					}
					?>
                    <li class="dropdown">
						<a href="#" class="dropdown-toggle textname" data-toggle="dropdown" role="button" aria-expanded="false" title="{{ ucwords(Auth::user()->name) }}" data-toggle="tooltip"><img width="35" height="35" alt="{{ Auth::user()->name }}" src="{{ $profile_picture }}" class="img-circle" > {{ ucwords(Auth::user()->name) }} <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
					@if($session == 1 )
					<input type="hidden" id="alert" name="alert" value="">
					<li><a href="javascript:void(0);" onclick="getAlert(); return false;">Profile</a></li>
					@elseif($session == 0)
					<li><a href="{{ url('/user/profile') }}">Profile</a></li>

					@endif
							<li role="separator" class="divider"></li>
							<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
						</ul>
					</li>
					@endif
				</ul>
			</div>
			@endif
		</div>
	</nav>
	
	<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>