$( document ).ready(function() {
    console.log( "ready!" );    
    $('#institution_id,#role_id').on("change",function(){
    	var instId = $(this).val();
    	$targetList = $('div#user-list');	
    	var data = $( "#institution_id,#role_id" ).serializeArray();
    	//console.log('selecte instId ' + instId);
    	$.ajax({
			type: "GET",
			data: data,
			url: userSearchRoute,
			success: function (data) {
				if (data.toLowerCase().indexOf("code") >= 0 && data.toLowerCase().indexOf("401") >= 0) {
					window.location.reload();
					return false;
				}
				if(data != ''){				
					$targetList.html(data);
				}
			}
		});
    });
    $('.searchfilter').on("click",function(e){    	
    	//console.log('searchfilter ');
    	$(this)
        .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        $('.searchfilter-body').toggleClass('hide show');
    });

	
});