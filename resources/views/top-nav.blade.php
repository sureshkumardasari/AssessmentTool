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
				<a class="navbar-brand" href="/"><img class="logo-img" src="{{ asset('/images/logo_white.png') }}"></a>
			</div>

			<div class="collapse navbar-collapse" id="top-navbar-collapse-1">
				@if (Auth::guest())
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/') }}">Home</a></li>
					<li><a href="{{ url('/') }}">About Us</a></li>
					<li><a href="{{ url('/') }}">Licensing</a></li>
					<li><a href="{{ url('/') }}">Contact Us</a></li>
				</ul>
				@else
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/') }}">Home</a></li>
					<li><a href="{{ url('/assessment/myassignment') }}">My Assignments</a></li>
					<li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Resources <span class="caret"></span></a>
			          <ul class="dropdown-menu">
			          	<li><a href="{{ url('/resources/category') }}">Category</a></li>
			            <li><a href="{{ url('/resources/subject') }}">Subjects</a></li>
			            <li><a href="{{ url('/resources/lesson') }}">Lessons</a></li>
			            <li role="separator" class="divider"></li>
			            <li><a href="{{ url('/resources/question') }}">Question</a></li>
			            <li><a href="{{ url('/resources/passage') }}">Passages</a></li>
			            <li role="separator" class="divider"></li>
			            <li><a href="{{ url('/resources/assessment') }}">Assessments</a></li>
			            <li><a href="{{ url('/resources/assignment') }}">Assignments</a></li>
			          </ul>
			        </li>
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administration <span class="caret"></span></a>
			          <ul class="dropdown-menu"> 
                       <li><a href="{{ url('/user/branding/brandview') }}">Branding</a></li>
			          	<li role="separator" class="divider"></li>
			            <li><a href="{{ url('/user/institution') }}">Institutions</a></li>
			            <li role="separator" class="divider"></li>
			            <li><a href="{{ url('/user') }}">Users</a></li>
			            <li role="separator" class="divider"></li>
			            <li><a href="{{ url('/user/role') }}">Roles</a></li>
			          </ul>
			        </li>
			        <li><a href="{{ url('/grading') }}">Grading</a></li>
					<li><a href="{{ url('/report') }}">Reports</a></li>
				</ul>
				@endif
				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
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
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><img width="50" height="50" alt="{{ Auth::user()->name }}" src="{{ $profile_picture }}" class="img-circle"> {{ ucwords(Auth::user()->name) }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/user/profile') }}">Profile</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>