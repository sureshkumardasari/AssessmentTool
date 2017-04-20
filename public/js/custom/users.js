$( document ).ready(function() {

     function filter(){ 
      // alert("safsdg"); 
  if($('#institution_id').val() == 0 || ($('#role_id').val() == 0)){
                alert("please select all the fields");
    }
            else{
     var institution_id = $('#institution_id').val();
     var role_id = $('#role_id').val();
       console.log( "ready!" ); 

     if(page == 'role_id')
     {
      $targetList = $('div#user-list'); 
     }
     
     
     var data = $( "#institution_id, #role_id").serializeArray();
     //console.log('selecte instId ' + instId);
     $targetList.html('');
     $("#loadingdiv").html('<img src="/images/fancybox_loading.gif" border="0"> Loading...');
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
                $("#loadingdiv").html('');
            }
        });
     }
 };
  
    $('.searchfilter').on("click",function(e){ 
    $('#institution_id').val('0');
    $('#role_id').val('0');
       	
    	//console.log('searchfilter ');
    	$(".searchfilter span")
        .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        $('.searchfilter-body').toggleClass('hide show');
    });

	
});