{{--*/
    $ans_count = 1;
/*--}}
@foreach($oldAnswers as $key => $answer)
    <div class='answer_container mb40 php_generated_answer'>
    <div class='mb18 mr10 mt20 pos_rel'>
        <div class='col-md-2'>
        <label class='mr20 mt8 w200 question_answer_count'>Answer #{{$ans_count}}<i>*</i></label>
        <input type="hidden" name="answerIds[]" class='hanswerId' value= {{ $answerIds[$key] }}>
        <input id='input-1' class='ans-chk' value={{$key+1}} type='checkbox' data-group-cls='btn-group-sm' offLabel='\"<span class=\"glyphicon glyphicon-remove\">\"' onLabel='\"<span class=\"glyphicon glyphicon-ok\">\"'>

<!--         <i class='{{ ($is_correct[$key] == "true") ? "switch_on" : "switch_off" }} icons L0 correct' data-answer_selection=''></i>
 -->        <input type='hidden' name='is_correct[]' value="{{ ($is_correct[$key] == "true") ? "true" : "false" }}"/>
        </div>
        <div class='col-md-10'>
        <p style='w93 fltL'>
            <textarea name='answer_textarea[]' id='answer_textarea_{{ uniqid() }}' class='answer_textarea required' data-type='tinymce' data-name='Answer Text' data-read_only='false'>{!! htmlspecialchars($answer) !!}</textarea>
            <div class='clr'></div>
        </p>
        </div>
        <div class='clr'></div>
    </div>
    <div class='mb18 mr10 mt20'>
        <div class='col-md-2'>
        <label class='mr20 mt8 w200'>Explanation</label>
        </div>
        <div class='col-md-10'>
        <div class='w742 fltL'>
            <textarea name='explanation[]' class='textarea w722 hgt125 create_inpt alphanumeric'>{{ $explanation[$key] }}</textarea>
            <div class='clr'></div>
            <p class='exp_links Lht30 mt15 mr0 fltR'><i class='del icons mr10 delBtn' id='del_{{ uniqid() }}'></i> Delete</p>
            <p class='exp_links mt20 mr30 fltR'><i class='{{ ($key == "0") ? "down" : "up" }} icons mr20 upDownBtn'></i>{{ ($key == "0") ? "Move Down" : "Move Up" }}</p>
            <div class='clr'></div>
        </div>
        </div>
        <div class='clr'></div>
    </div>
</div>
{{--*/
    $ans_count++;
/*--}}
@endforeach
<?php $i=0;//dd($is_correct);
if(count($is_correct)>0){?>
<script>
 tinymce.init({
  selector: '.answer_textarea',
  width : 650,
  height : 135,
  auto_focus:false,
  statusbar : false,
  menubar : false,
  paste_as_text: true,
  toolbar: 'bold,italic,underline',
 });

$('#ans_flg').val({{count($is_correct)}});
var i=0;
var is_correct=new Array();
var myForm = document.forms.qst_form;
var myControls = myForm.elements['is_correct[]'];
               // var myControls = myForm.elements['is_correct[]'];

<?php 
foreach($is_correct as $correct){
    ?>
    is_correct.push("<?php echo $correct; ?>");
<?php } 
?>
i=0;
$('.ans-chk').each(function(index, elem) {
    if(is_correct[i]=="true")
    {
    myControls[i++].value = "true";
     $(this).prop('checked',true);
    }
 else if(is_correct[i]=="false") {
        myControls[i++].value = "false";
        $(this).prop('checked',false);
     }
   });
</script>
<?php } ?>