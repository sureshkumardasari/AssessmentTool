$(document).ready(function() {  
   $(document).on('click','#grade',function(e){
          e.preventDefault();
          mapTollTip($(this));
        
    });
});
function mapTollTip(tipTarget){
  // removing already open tool tip if it exists.
    $('.tip-container').remove();
      var formativeUrl = $(tipTarget).attr('formative-url');
      var questionUrl = $(tipTarget).attr('question-url');
      var val = tipTarget.html();
      var OkBtnText     = "Grade by Student";
      var cnclBtnText   = "Grade by Question";
      var headerText    = "";
      var okclassName   = 'btn-grade';
      var cnclclassName = 'btn-grade-cncl';
 
  myDialog(tipTarget, {
      headerText: "",
      message: headerText,
      buttons: [
          {
              text: OkBtnText,
              className: okclassName,
              click: function(e) {
                if(typeof id =="undefined")
                    window.location.href=formativeUrl;
                else
                    sendRequest('delete',id);
              }
          }, {
              text: cnclBtnText,
              className: okclassName,
              click: function(e) {
                if(typeof id =="undefined"){
                    window.location.href=questionUrl;
                } else {
                     e.preventDefault();
                    // $('.ssi-tip').remove();
                    // $('.tip-container').remove();
                }
              }
          }
      ]
  });
  $('.tip-container').addClass('r0');
  $("input.orderTopicToolTip").val(val);
//            $('.ssi-tip').attr('style','top: 483px; left: 1048.5px;');
//            var val = $("input.orderTopicToolTip").val();
}