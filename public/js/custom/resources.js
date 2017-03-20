$( document ).ready(function() {
       
    $('#institution_id, #category_id, #subject_id').on("change",function(){
     var thisId = $(this).attr('id');
     var institution_id = $('#institution_id').val();
     var category_id = $('#category_id').val();
     var subject_id = $('#subject_id').val();
  console.log( "ready!" ); 
     var page = $('#page').val();
     
     if(thisId == 'institution_id')
     {
      if(page == 'subject' || page == 'lesson' || page == 'subjectedit' || page == 'lessonedit')
      {
       $targetFilter = $('select#category_id');
       var data = $( "#institution_id" ).serializeArray();
       $.ajax({
     type: "GET",
     data: data,
     url: categoryRoute,
     success: function (data) {
      if (data.toLowerCase().indexOf("code") >= 0 && data.toLowerCase().indexOf("401") >= 0) {
       window.location.reload();
       return false;
      }
      var optionsdata = '<option value="0">Select</option>';
      if(data != ''){    
       var arr = $.parseJSON(data);
       $.each(arr, function (index, value) {

           optionsdata += '<option value="'+index+'">'+value+'</option>';
       });
      }
      $targetFilter.html(optionsdata);
 		//var optionsdata = '<option value="0">Select</option>';
      $('select#subject_id').html('<option value="0">Select</option>');
     }
    }); 
      }
     }
     if(thisId == 'category_id')
     {
      if(page == 'lesson' || page == 'lessonedit')
      {
       $targetFilter = $('select#subject_id');
       var data = $( "#institution_id, #category_id" ).serializeArray();
       $.ajax({
     type: "GET",
     data: data,
     url: subjectRoute,
     success: function (data) {
      if (data.toLowerCase().indexOf("code") >= 0 && data.toLowerCase().indexOf("401") >= 0) {
       window.location.reload();
       return false;
      }
      var optionsdata = '<option value="0">Select</option>';
      if(data != ''){  
       var arr = $.parseJSON(data);
       $.each(arr, function (index, value) {
           optionsdata += '<option value="'+index+'">'+value+'</option>';
       });
      }
      $targetFilter.html(optionsdata);
     }
    }); 
      }
     }     
    });

 $("#applyFiltersBtn").on("click",function(e){    
  
     var institution_id = $('#institution_id').val();
     var category_id = $('#category_id').val();
     var subject_id = $('#subject_id').val();
  console.log( "applyFiltersBtn!" ); 
     var page = $('#page').val();
     if(page == 'category')
     {
      $targetList = $('div#category-list'); 
     }
     else if(page == 'subject')
     {
      $targetList = $('div#subject-list'); 
     }
     else if(page == 'lesson')
     {
      $targetList = $('div#lesson-list'); 
     }
     
     var data = $( "#institution_id, #category_id, #subject_id" ).serializeArray();
     //console.log('selecte instId ' + instId);
     $targetList.html('');
     $("#loadingdiv").html('<img src="/images/fancybox_loading.gif" border="0"> Loading...');
     $.ajax({
   type: "GET",
   data: data,
   url: searchRoute,
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
 });

    $('.searchfilter').on("click",function(e){     
     //console.log('searchfilter ');
     $(".searchfilter span")
        .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        $('.searchfilter-body').toggleClass('hide show');
    });

 
});