<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	@section('header-assets')
		<link rel="stylesheet" href="{{ asset('css/pdf.css') }}">
		<style>			
			body {font: 12px helvetica; color: #403f3c; margin: 0px; background-color: #fff; color: #444; text-shadow: none; height: 30mm;}
			.header {width: 800px; margin: auto; overflow: hidden;}
			.wrapper { background-color: white;   overflow: hidden; height: 100%;}
			table, tr, td, th, tbody, thead, tfoot {page-break-inside: avoid !important; }
			.break {page-break-before:always; }			
			.header img, .footer img {width: 100%;} .mh10 {min-height: 10px;} 
			table {border: 1px solid #000; border-collapse: collapse; margin: 0 auto; }
			table td {border: 1px solid #000; padding-left: 5px; padding-right: 5px; } 
			img {display: block; margin: 0 auto; max-width:200px; }
			img.Wirisformula{display: inline; max-width: none !important; }
		    .MathJax, .MathJax span {font-weight: normal !important; }		
		    .wrapper .header ul, .wrapper .header ol {margin: 0 auto; }
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
		
		<div class="header" style="text-align:center;border-bottom:1px solid #000;height:auto;word-wrap: break-word;">			
			{!! $header !!}			
		</div>		
	</div>
</body>
</html>