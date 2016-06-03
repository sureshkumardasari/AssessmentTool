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
	margin-bottom:30px;
}
.depatment-text .boldtext {
	font-size:18px;
	line-height:25px;
	color:#403f3c;
	font-family:'droid_sansbold';
	margin-bottom:30px;
	display:block;
}
.depatment-text .boldtext .time-color {
	color:#e97564;
}
</style>
<section class="msgs_box">
    <section class="drop_downs no-bdr"> 
        <!--place your body content here.-->
        <div class="score_pop pb30 w575 mt10">
            <div class="department-heading bdr_bottom">
                <h1 class="txt_32_b pL30 fltL">End Instructions</h1>
                <div class="clr"></div>
            </div>
            <div class="depatment-text pL30 pR30">
          	    {!! $endInstructions !!}
                <span class="boldtext">Are you sure that you want to submit the test now?<span id="main-container"> You have <span id="unanswered-count-container"><span class="time-color" id='unanswered-count'>0</span> <span id="answerText">unanswered questions</span><span id="dot-container">.</span></span> <span id='ttr-display-container'> <span id="and-container">and</span> <span class="time-color" id='ttr-display'>00:00:00</span> remaining.</span></span></span>
            </div>
          
            <div class="clr mL30">
                <a href="javascript:void(0)" class="btn btn-primary" id='btn-yes-submit'>Yes</a>
                <a href="javascript:void(0)" class="btn btn-primary" id='btn-no'>NO</a>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </section>
    <div class="clr"></div>
</section>

<script type="text/javascript">
   // $(document).ready(function() {

        $('#dot-container').hide();

        if(getUnansweredCount() == 1){
            $('#answerText').text('unanswered question');
        }
        else{
            $('#answerText').text('unanswered questions');
        }

        if(getUnansweredCount() == 0){
            $('#unanswered-count-container').hide();
            $('#and-container').hide();
        }
        if ($('#ttr').size() == 0) {
            $('#ttr-display-container').hide();
            if(getUnansweredCount() == 0){
                $('#main-container').hide();
                $('#dot-container').hide();
            }
            else{
                $('#dot-container').show();
            }
        }

        $('#ttr').bind("DOMSubtreeModified", function() {
            $('#ttr-display').text($('#ttr').text());
        });

        $('body').on('click', '#btn-no', function() {
            $.fancybox.close();
        });
           $('body').on('click', '#btn-yes-submit', function() {
                submitTest(false);
           });
        $('#unanswered-count').text(getUnansweredCount());
   // });
</script>