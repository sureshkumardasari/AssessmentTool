<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Assessment PDF</title>

	@section('header-assets')
		<link rel="stylesheet" href="{{ asset('css/pdf.css') }}">
		<style>
		
			@font-face {font-family: MathJax_Main; src: url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/woff/MathJax_Main-Regular.woff?rev=2.5.3') format('woff'), url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/otf/MathJax_Main-Regular.otf?rev=2.5.3') format('opentype')}
			@font-face {font-family: MathJax_Main; src: url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/woff/MathJax_Main-Bold.woff?rev=2.5.3') format('woff'), url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/otf/MathJax_Main-Bold.otf?rev=2.5.3') format('opentype'); font-weight: bold}
			@font-face {font-family: MathJax_Main; src: url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/woff/MathJax_Main-Italic.woff?rev=2.5.3') format('woff'), url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/otf/MathJax_Main-Italic.otf?rev=2.5.3') format('opentype'); font-style: italic}
			@font-face {font-family: MathJax_Math; src: url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/woff/MathJax_Math-Italic.woff?rev=2.5.3') format('woff'), url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/otf/MathJax_Math-Italic.otf?rev=2.5.3') format('opentype'); font-style: italic}
			@font-face {font-family: MathJax_Caligraphic; src: url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/woff/MathJax_Caligraphic-Regular.woff?rev=2.5.3') format('woff'), url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/otf/MathJax_Caligraphic-Regular.otf?rev=2.5.3') format('opentype')}
			@font-face {font-family: MathJax_Size1; src: url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/woff/MathJax_Size1-Regular.woff?rev=2.5.3') format('woff'), url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/otf/MathJax_Size1-Regular.otf?rev=2.5.3') format('opentype')}
			@font-face {font-family: MathJax_Size2; src: url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/woff/MathJax_Size2-Regular.woff?rev=2.5.3') format('woff'), url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/otf/MathJax_Size2-Regular.otf?rev=2.5.3') format('opentype')}
			@font-face {font-family: MathJax_Size3; src: url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/woff/MathJax_Size3-Regular.woff?rev=2.5.3') format('woff'), url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/otf/MathJax_Size3-Regular.otf?rev=2.5.3') format('opentype')}
			@font-face {font-family: MathJax_Size4; src: url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/woff/MathJax_Size4-Regular.woff?rev=2.5.3') format('woff'), url('https://www.tuhh.de/MathJax/fonts/HTML-CSS/TeX/otf/MathJax_Size4-Regular.otf?rev=2.5.3') format('opentype')}

			body {font: 18px helvetica; color: #403f3c; margin: 0px; background-color: #fff; color: #444; text-shadow: none;}
			table, tr, td, th, tbody, thead, tfoot {page-break-inside: avoid !important; }
			.break {page-break-before:always; }					    
		    .side-area {float:right; } 
		    .side-area .num {float:right; padding-top:0px; font-size:30px; letter-spacing:5px; }
		    .side-area .num .color {color:#e97564; font-family: helvetica; font-weight: bold;}
		    .view-area .content {font-size:18px;line-height: 20px;}  
		    .inline {display: inline-block;}
		    .view-area {width: 830px; margin: auto; }
		    .page {min-height: 1000px;}
		    .page .line { height: 30px; display: block; border-bottom: 1px solid black; }
		    .questions_passage .bottom_bullet_spn{  position: relative; display: inline; vertical-align: top; padding: 0 0 20px; }
		    .bottom_bullet {position: absolute; bottom: 0; left: 0; right: 0; font-size: 9px !important; top: 17px;height: 13px; overflow: hidden; }
		    .mh10 {min-height: 10px;}
			.content { word-break: break-word;}
		    td, th, caption{border:1px solid #000; padding:5px; border-collapse: collapse; margin:0 auto; }
		    img.Wirisformula{display: inline; max-width: none; }
		    .view-area #content .question p {     margin: 0 0 17px; line-height: 20px; }
		    .view-area #content .question .other_content_data p { margin-bottom: 0; }		    
		    .questions_passage .bottom_bullet_spn{  position: relative; display: inline-block; vertical-align: top; padding: 0 0 20px; }
		    .bottom_bullet { position: absolute; bottom: 0; left:0; right: 0; text-align: center; }
		    .column > * { padding: 10px }
		    .column img{max-width: 100%;}
		    .first.column{word-break: break-word !important; }
		    .first_child img, .other_question img{max-width: 100%; }
		    .text-1 {float: left; margin-top: 20px; }
		    .fltL {float: left !important;}
		    .fltR {float: right !important; }
		    .grid-item {width: 380px !important;border: 0px !important;padding: 0px !important;    padding-right: 0px !important;}
		    .grid-right {margin-left: 15px;}	
		    .view-area img:not(.Wirisformula) {max-width: 100%;margin: auto; display: block;height: auto;padding-top: 5px; padding-bottom: 5px;}
		    .view-area div.clr {height: 0px;    line-height: 0px;}
		    table {margin: auto;}		    
		</style>

		@if ($parentId == 1)
			<style>
				.view-area {width: 816px !important; margin: auto; overflow: hidden;}
				.page {width: 815px;}
			</style>
		@endif

		@if ($parentId == 2)
			<style>
				.view-area {width: 870px !important; }
				.grid-item {width: 380px !important;overflow: hidden;}
				.view-area table {    word-break: break-all; width: 100% !important;}
			</style>
		@endif
		@if ($parentId == 3)
			<style>				
				.grid-item {width: 380px !important;overflow: hidden;}				
				.view-area table {    word-break: break-all; width: 100% !important;}
			</style>
		@endif
	@show

</head>
<body>
	<div class="view-area">		
		{!! $content !!}
    </div>
</body>
</html>