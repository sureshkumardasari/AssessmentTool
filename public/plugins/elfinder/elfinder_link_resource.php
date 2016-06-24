<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Insert Image</title>

		<link rel="stylesheet" type="text/css" media="screen" href="css/jquery-ui.css">		
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		
		<link rel="stylesheet" type="text/css" media="screen" href="css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/theme.css">
		
		<script type="text/javascript" src="js/elfinder.min.js"></script>
		<script type="text/javascript" src="js/i18n/elfinder.ru.js"></script>

		<style>
			.elfinder-navbar, .elfinder-statusbar {display: none !important; }
		</style>
		<script type="text/javascript" charset="utf-8">

			$().ready(function() {
				var elf = $('#elfinder').elfinder({
					url : 'php/connector.php?from=messaging',
//					onlyMimes: ["image"],
					height: 420,
					commands : [
					    'open', 'reload', 'home', 'up', 'back', 'forward', 'getfile', 'quicklook', 
					    'download', 'duplicate', 'upload', 'search', 'view'
					],
					getFileCallback: function(file) {
						window.opener.processFile(file);
                        window.close();
					},
					rememberLastDir: false,
					contextmenu : {
					    // current directory menu
					    cwd    : ['reload', 'upload'],

					    // current directory file menu
					    files  : [
					        'getfile', '|', 'download', '|', 'duplicate'
					    ]
					},
				}).elfinder('instance');

				$('.elfinder-toolbar .ui-widget-content').last().remove();
				$('.ui-resizable-handle').remove();
				// $('.elfinder-button-icon-back').closest('.ui-widget-content').remove();
				$('.elfinder-button-icon-info').closest('.ui-widget-content').remove();				
				$('.elfinder-button-icon-open').parent().next('.elfinder-toolbar-button-separator').remove();
				$('.elfinder-button-icon-open').parent().remove();								
			});
		</script>
	</head>
	<body>
		<div id="elfinder"></div>
	</body>
</html>
