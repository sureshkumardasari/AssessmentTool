$(document).ready(function () {

	tinymce.init({
	    selector: '#passage_text',
	    width : isMacintosh() ? 832 : 847,
	    height : 200,
	    auto_focus:false,
	    statusbar : false,
	    menubar : false,
	    toolbar: toolbar,
	    plugins: 'tiny_mce_wiris, paste, image, code',
	    image_advtab: true,
	    file_picker_types: 'image',
	    file_browser_callback:elFinderBrowser,
	    relative_urls : false,
	    remove_script_host : false,
	    convert_urls : true,
	    setup : function(ed) {
	        ed.on('init', function(args) {

	            // never delete this line its been added for cross screen resolution support
	           // $('#passageTextArea_ifr').contents().find('body').css({'width': '816px'});
	        });
	    }
	});

});