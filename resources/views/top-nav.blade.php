	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- <a class="navbar-brand" href="#">Laravel</a> -->
				<a class="navbar-brand" href="#"><img class="logo-img" src="{{ asset('/images/AppsTek-Corp-logoH.png') }}"></a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
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
					<li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Resources <span class="caret"></span></a>
			          <ul class="dropdown-menu">
			            <li><a href="{{ url('/resources/subject') }}">Subjects</a></li>
			            <li><a href="{{ url('/resources/lesson') }}">Lessons</a></li>
			            <li role="separator" class="divider"></li>
			            <li><a href="{{ url('/quesion') }}">Question</a></li>
			            <li><a href="{{ url('/passage') }}">Passages</a></li>
			            <li role="separator" class="divider"></li>
			            <li><a href="{{ url('/assessment') }}">Assessments</a></li>
			          </ul>
			        </li>
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administration <span class="caret"></span></a>
			          <ul class="dropdown-menu">
			            <li><a href="{{ url('/user/institution') }}">Institutions</a></li>
			            <li role="separator" class="divider"></li>
			            <li><a href="{{ url('/user') }}">Users</a></li>
			            <li role="separator" class="divider"></li>
			            <li><a href="{{ url('/user/role') }}">Roles</a></li>
			          </ul>
			        </li>
				</ul>
				@endif
				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><img width="50" height="50" alt="{{ Auth::user()->name }}" src="{{ asset('/images/profile_pic.jpg') }}" class="img-circle"> {{ ucwords(Auth::user()->name) }} <span class="caret"></span></a>
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