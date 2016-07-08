@extends('default')

@section('content')
	<style>
		.flash {
			position:absolute;
			z-index:1;
			left:600px;
			width: 300px;
			border: 5px;
			background-color:grey;
			padding: 10px;
			margin: 25px;
			}
	</style>
	@if(Session::has('flash_message'))

		<div class="flash">
		<p>
			<b>
				{{ Session::pull('flash_message') }}
			</b>
		</p>
	</div>
		@endif
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>

				<div class="panel-body">
					You are logged in!
				</div>
			</div>
		</div>
	</div>
</div>
	<script>
		setTimeout(function() {
			$('.flash').fadeOut('fast');
		}, 500); // <-- time in milliseconds
	</script>

@endsection
