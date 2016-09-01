<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	@section('header-assets')
		<link rel="stylesheet" href="{{ asset('css/pdf.css') }}">
		<style>
			body, .wrapper { background-color: white; }
		</style>
	@show

	<script>
		function subst() {
	  		var vars={};
	  		var x=document.location.search.substring(1).split('&');
	  		for (var i in x) {var z=x[i].split('=',2);vars[z[0]] = unescape(z[1]);}
	  		var x=['frompage','topage','page','webpage','section','subsection','subsubsection'];
	  		for (var i in x) {
	    		var y = document.getElementsByClassName(x[i]);
	    		for (var j=0; j<y.length; ++j) y[j].textContent = vars[x[i]];
	  		}
		}
	</script>

</head>
<body>
	<div class="wrapper @yield('wrapper-class')">
		
		<div class="header">
			<div style="float:left; width: 800px;">
				{!! $footerMeta['left'] !!}
			</div>
<!--			<div style="float:right; text-align: right; ">
				Page <span class="page"></span> of <span class="topage"></span>
			</div>-->
			<div class="clr"></div>
		</div>		
	</div>
</body>
</html>