<style>
    .depatment-text {
        font-size:14px;
        line-height:24px;
        color:#6d6f75;
    }
    .department-heading {
        margin-bottom:20px;
        padding-bottom:8px;
    }
    .depatment-text p {
        margin-bottom:20px;
    }
    .fib-area {
        padding:0 30px 30px;
    }
    .fib-area .fib-text {
        height:585px;
        width:755px;
        -webkit-box-shadow: inset 0 4px 5px rgba(0,0,0,.2);
        -moz-box-shadow: inset 0 4px 5px rgba(0,0,0,.2);
        box-shadow: inset 0 4px 5px rgba(0,0,0,.2);
        background:#fff;
        padding:15px;
        font-size:18px;
        line-height:25px;
        border:none;
        font-family:'droid_sansregular';
        border-bottom:1px solid #ccc;
        resize:none;
    }
</style>
<section class="msgs_box">
    <section class="drop_downs no-bdr">
    <!--place your body content here.-->
        <div class="score_pop pb30 w855 mt10">
            <div class="department-heading bdr_bottom">
                <h1 class="txt_32_b pL30 fltL">Fill in the blank</h1>
                <i class="icons cross_icon btn-close" id='close'></i>
                <div class="clr"></div>
            </div>
            <div class="depatment-text pL30 pR30">
                {!! $questionText !!}
            </div>
            <div class="fib-area">
                <textarea class="fib-text" cols="50" role="80" maxlength="5000" name="fib_text" data-subsecquestionid="{{ $subSecQuestionId }}">@if(!empty($oldEssay)) {!! str_replace("<br />", "\n", $oldEssay->question_answer_text) !!} @endif</textarea>
            </div>
            <div class="clr mL30">
                <a href="javascript:void(0)" class="upload_btn pL50 pR50 mL0 mt0 fltL" id="btn-fib-save">Save</a>
                <a href="javascript:void(0)" class="upload_btn pL50 pR50 mL0 mt0 fltL btn-close" id="btn-close">Close</a>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </section>
    <div class="clr"></div>
</section>
<script type="text/javascript">
    $(document).ready(function() {
        window.essay_isDirty = false;
    });

    $('.btn-close, #btn-fib-save').on('click', function() {
        $('#ff_essay_{{ $subSecQuestionId }}').data('essay', $('.fib-text').val().trim());
    });
</script>