$(document).ready(function () {

	tinymce.init({
	    selector: '#passage_lines',
	    width : 371,
	    height : 200,
	    auto_focus:false,
	    statusbar : false,
	    menubar : false,
	    toolbar: 'underline',
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

 extendJqueryForDataSelection();    

    if (tinyMCE.get("passage_text") != null) {
        tinyMCE.get("passage_text").remove();                
    }
    tinymce.init({
        selector: '#passage_text',
         width : isMacintosh() ? 560 :597,
        height : 200,
        auto_focus:false,
        statusbar : false,
        menubar : false,
        toolbar: 'code,|,bold,italic,|,cut,copy,paste,|,undo,redo, | alignleft, aligncenter, alignright, alignjustify, | bullist numlist |,tiny_mce_wiris_formulaEditor | image',
        plugins: 'tiny_mce_wiris, paste, image, code',
        image_advtab: true,
        file_picker_types: 'image',
        file_browser_callback:elFinderBrowser,
        relative_urls : false,
        remove_script_host : false,
        convert_urls : true,
        setup : function(ed) {            
        }
    });

});

function extendJqueryForDataSelection(){
	$.extend( $.expr[ ":" ], {
	    data: $.expr.createPseudo ?
	        $.expr.createPseudo(function( dataName ) {
	            return function( elem ) {
	                return !!$.data( elem, dataName );
	            };
	        }) :
	        // support: jQuery <1.8
	        function( elem, i, match ) {
	            return !!$.data( elem, match[ 3 ] );
	        }
	});
}

function elFinderBrowser(field_name, url, type, win) {
	$.fancybox({
	    'width': '903',
	    'height': '489',
	    'autoScale': true,
	    'transitionIn': 'fade',
	    'transitionOut': 'fade',
	    'type': 'ajax',
	    'href': fileBrowser,
	    afterClose: function () {
	        //triggering global function on close if you want to implement any logic onclose then contact rdia@bm
	        if(window.selectedItem != undefined){
	            win.document.getElementById(field_name).value = window.selectedItem;
	        }
	    }
	});
}

function isMacintosh() {
    return navigator.platform.indexOf('Mac') > -1
}