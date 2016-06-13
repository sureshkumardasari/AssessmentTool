@extends('default')
@section('content')


<style>
    section .msgs_links ul li {
        margin-left: 0 !important;
    }
    .move-to-next {
        top: 300px !important;
    }
    .fancybox-inner{
        height: auto !important;
    }
    .fancybox-inner .fancybox-iframe{
        min-height: 700px !important;
        height: auto !important;
    }

    .question > p >img{
        display :block !important;
    }
</style>
<section class="assesmant-q-details msgs_box">
    <div class="msgs_links mb24">

        @if( isset($assignment->assessment->testTypes->Display ) )
            <input type="hidden" name="assmt_test_type" value="{{ $assignment->assessment->testTypes->Display }}" id="assmt_test_type" class="assmt_test_type">
        @else
            <input type="hidden" name="assmt_test_type" value="Formative" id="assmt_test_type" class="assmt_test_type">
        @endif

        <a class="create_new_btn btn-create-narrow mR0 mt0 fltR" href="{{route('studentGrading',['type'=>$type,'id'=>$assignmentId])}}">Return to Page</a>
        {{--<a class="create_new_btn btn-create-narrow mR0 mt0 fltR" href="{{route('studentGrading',['type'=>$type,'id'=>$assignmentId])}}">Clear Grade</a>--}}

        <a href="javascript:void(0)"  id="clear" data-url="{{route('clearStudentGrade')}}" class="create_new_btn btn-create-narrow mR0 mt0 fltR clear">Clear Grade</a>
        <h1 class="fltL">{{ $assignment->Name }}</h1>
        <div class="clr"></div>
    </div>
    <div class="pL15 pR15 assment-header">
        <div class="pb0 w100p fltL">
            <div class="pb16">

                <?php $studentIndex = 0; ?>
                <div class="msgs_links pb20 mb20">
                    <div class="fltL mr30">
                        <label class="txt_17_b w140 fltL mt9 mL4">Student Name</label>
                        <select name="studentId" id="drpAssignmentStudent" class="custom_slct filter-listing w200">
                            @foreach( $assignment->assignedUsers as $i=> $assgUser )

                                <?php
                                $isGradeNext =  getNextGradingStudent($assignment->Id,$assgUser->user->id,$sectionId);
                                ?>
                                <option is-graded-next = "{{(empty($isGradeNext)?'next':'no')}}" {{ ($assgUser->user->id == $userId) ? 'selected' : '' }} value="{{ $assgUser->user->id }}">{{ $assgUser->user->name }}</option>
                                @if($assgUser->user->id == $userId)
                                    $studentIndex = $i;
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="fltL mt8">
                        Institution:
                                 <span class="institutions">
					@foreach($user->institutions as $i=> $institution)
                                         @if($i > 0)
                                             {{','}}
                                         @endif
                                         {{$institution->Name}}
                    @endforeach
				</span>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="mL20 pb10">

                    <?php $date=date_create($assignmentUser->TakenDate); ?>
                    <span class="txt_17_b w140 fltL">Date Taken:</span><span class="date-taken">{{(!empty($assignmentUser->TakenDate)?date_format($date,"m/d/Y"):'')}}</span>
                    <div class="clr"></div>
                </div>
                <div class="mL20">

                    <?php $date=date_create($assignmentUser->GradedDate); ?>
                    <span class="txt_17_b w140 fltL">Date Graded:</span><span class="date-graded">{{(!empty($assignmentUser->GradedDate)? date_format($date,"m/d/Y") :'')}}</span>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>

    {!! $userGradingView !!}

    <div class="clr"></div>
</section>

@endsection
@section('footer-assets')
    @parent
    {!! HTML::script(asset('assets/js/reports/fusion-charts/fusioncharts.js')) !!}
    {!! HTML::script(asset('assets/js/reports/fusion-charts/fusioncharts.charts.js')) !!}
    {!! HTML::script(asset('assets/js/reports/fusion-charts/fusioncharts.powercharts.js')) !!}

    <script type="text/javascript">
        updateButtonBehaviour();
        $(".fancybox_no_close_click").fancybox({
            href: $(this).prop('href'),
            wrapCSS: 'fancybox-custom',
            closeClick: false, // prevents closing when clicking INSIDE fancybox
            enableEscapeButton: false,
            type: 'iframe',
            keys: {
                close: null
            },
            helpers: {
                overlay: {closeClick: false}, // prevents closing when clicking OUTSIDE fancybox
            }
        });

        function showErrors ( errors ) {
            $('#error_container ul').html('<li>' + errors.join('</li><li>') + '</li>');
            $('#show_error_popup').trigger('click');
        }

        function calculateStats() {
            /*var totalAttempted = $('.question-points').not($('.essay-question, .no-response-question')).length,
                    totalCount = $('.question-points').length,
                      responses = $('.question-points').not('.essay-question, .no-response-question').length;
            */
            var totalAttempted = $('.question-points').not($('.no-response-question')).length,
                    totalCount = $('.question-points').length,
                    responses = $('.question-points').not('.no-response-question').length;
            
            // responses = $('.question-points').not('.essay-question.no-response-question, .not-graded').length;
            //noResponses     = $('.no-response-question').length,
            noResponses = (totalCount - responses),
                    haveResponse   = responses ? responses : 0,
                    noResponse     = noResponses ? noResponses : 0,
                    percentage     = ( haveResponse / totalCount ) * 100,
                    noResponse     = ( noResponse / totalCount ) * 100;
            console.log(' totalCount ' +totalCount + ' responses ' +responses + ' percentage ' +percentage);
            // Shows the questions for which there is a response.
            renderGraph({
                renderAt: 'complete-graph',
                centerLabel: ( Math.round(percentage, 2) || 0 ) + '%',
                paletteColors: '#F6D503,#8DCA41',
                graphData: [
                    { "label": "No Response", "value": noResponse },
                    { "label": "Complete", "value": percentage }
                ]
            });

            //$('.essay-question.no-response-question').parent('.question-item').addClass('no-response-question');
            $('.no-response-question').closest('.question-item').addClass('no-response-question');
            $('.question-item.no-response-question').find('input.achv-points').addClass('no-response-question');
            if(!$('.essay-question').hasClass('.no-response-question')){
                $(this).parent('.question-item').removeClass('no-response-question');
            }
            if(!$('.question-item').hasClass('.no-response-question')){
                $(this).find('input.achv-points').removeClass('no-response-question');
            }

//        debugger;
            /* var obtainedPointsData = $.makeArray($('.achv-points').not('.qessay-grade').map(function(){ return ($(this).text() == '' ) ? ((!isNaN(parseInt($(this).val())) && parseInt($(this).val()) > 0)  ? parseInt($(this).val()) : 0) : ((!isNaN(parseInt($(this).text())) && parseInt($(this).text()) > 0) ? parseInt($(this).text()) : 0)}));
            */
            var obtainedPointsData = $.makeArray($('.achv-points').map(function(){ return ($(this).text() == '' ) ? ((!isNaN(parseInt($(this).val())) && parseInt($(this).val()) > 0)  ? parseInt($(this).val()) : 0) : ((!isNaN(parseInt($(this).text())) && parseInt($(this).text()) > 0) ? parseInt($(this).text()) : 0)}));

            var obtainedPoints = 0;
            var percentage = 0;
            
            if (obtainedPointsData.length>0) {
                var obtainedPoints = obtainedPointsData.reduce(function(a, b){return a+b});
            }
            /*            
            var totalPointsData = $.makeArray($('.question-item').not('.essay-ques-item').find('.total-q-points').map(function(){return parseInt($(this).text());}));
            */
            var totalPointsData = $.makeArray($('.question-item').find('.total-q-points').map(function(){return parseInt($(this).text());}));
            if (totalPointsData.length>0) {
                var totalPoints = totalPointsData.reduce(function(a, b){return a+b});
                percentage = ( obtainedPoints / totalPoints ) * 100;
            }
            // console.log(totalPointsData);

            //console.log(obtainedPointsData, totalPointsData);
            //var wrongResponse = $('.question-points.wrong-question').length,
            //  correctResponse = $('.question-points.correct-question').length,
            //percentage = ( correctResponse / totalAttempted ) * 100;
            // var percentage = ( obtainedPoints / totalPoints ) * 100;

            // Shows the questions for which there is a correct response.
            renderGraph({
                renderAt: 'correct-graph',
                centerLabel: (Math.round(percentage, 2) || 0) + '%',
                paletteColors: '#EE715F,#A1CB5B',
                graphData: [
                    { "label": "Wrong", "value": (100 - percentage) },
                    { "label": "Correct", "value": percentage }
                ]
            });
        }

        function renderGraph (options) {
            var revenueChart = new FusionCharts({
                type: 'doughnut2d',
                renderAt: options.renderAt,
                width: '270',
                height: '270',
                dataFormat: 'json',
                dataSource: {
                    "chart": {
                        "caption": "",
                        "subCaption": "",
                        "numberPrefix": "%",
                        "paletteColors": options.paletteColors,
                        "bgColor": "#ffffff",
                        "showBorder": "0",
                        "use3DLighting": "0",
                        "showShadow": "0",
                        "enableSmartLabels": "0",
                        "startingAngle": "310",
                        "showLabels": "0",
                        "showValues": "0",
                        "showPercentValues": "0",
                        "showLegend": "0",
                        "legendShadow": "0",
                        "legendBorderAlpha": "0",
                        "defaultCenterLabel": options.centerLabel,
                        "showTooltip": "1",
                        "decimals": "0",
                        "captionFontSize": "14",
                        "subcaptionFontSize": "14",
                        "subcaptionFontBold": "1",
                        "centerLabelFontSize": "20",
                        "chartLeftMargin": "0",
                        "chartRightMargin": "0",
                        "doughnutRadius": "50"
                    },
                    "data": options.graphData
                }
            }).render();
        }

        function persistTempValues () {
            var volatileInputs = $('.grading-section-container .essay-ques-item input[type="text"]:visible')
                    .add('.grading-section-container .essay-ques-item textarea:visible')
                    .add('.grading-section-container #assessment-comment'),
                    persistedValues = {};

            volatileInputs.each(function( index, elem ){
                var key = $(elem).attr('id');
                if( key && !$(elem).is('[readonly]')) {
                    persistedValues[ key ] = $(elem).val();
                }
            });

            window.persistedValues = persistedValues;
        }

        function retrieveTempValues () {
            if ( window.persistedValues ) {
                for ( var elemId in window.persistedValues ) {
                    $('#' + elemId).val( window.persistedValues[ elemId ] );
                }
            };
        }

        function updateButtonBehaviour () {
            var nextOption = $('#drpAssignmentStudent').find('option:selected').next().attr('is-graded-next');
            if ( nextOption !== "no" || typeof nextOption=="undefined" ) {
                $('#save-continue-grading').val('Save and Finish');
            };
        }

        function fetchGrading ( persistValues ) {
            var userId       = $('#drpAssignmentStudent').val(),
                    assignmentId = {{ $assignment->Id }},
                    sectionId    = {{$sectionId}};

            $.ajax({
                url: "{{ route('fetchVerticalGradeStudent') }}",
                data: { userId: userId, assignmentId : assignmentId,sectionId:sectionId },
                beforeSend: function () {
                    // toggleMsg('Please wait!');
                    if ( persistValues === true ) {
                        persistTempValues();
                    } else {
                        delete window.persistValues;
                    }
                },
                success: function ( data ) {
                    $('.grading-section-container').html( data );
                },
                complete: function () {
                    // toggleMsg();
                    calculateStats();

                    if ( persistValues === true ) {
                        retrieveTempValues();
                    } else {
                        delete window.persistValues;
                    };

                    updateButtonBehaviour();
                    hideMoveToQuestion();
                }
            });

            $.ajax({
                url: "{{ route('fetchVerticalGradeStudentDate') }}",
                data: { userId: userId, assignmentId : assignmentId },
                dataType: 'json',
                success: function ( data ) {
                    $('.date-taken').text(data.dateTaken);
                    $('.date-graded').text(data.dateGraded);
                },
            });


        }

        function hideMoveToQuestion(){
            if( $('.essay-not-graded').length != "0") {
                $('.move-to-next').removeClass('hideBtn');
            }
        }


        function sendRequest(data) {
            $.ajax({
                url:data.url,
                method: 'post',
                data: {
                    id: data.assignmentId,
                    userId:data.userId,
                    sectionId:data.sectionId
                },
                success: function(response) {
                    if(response=='Success'){
                        showMsg('Grade cleared successfully');
                        window.setTimeout(function(){
                            window.location.reload();
                        }, 2000);
                    }

                },
                complete: function () {
                    fetchGrading();
                }
            });
        }


        function mapTollTip(tipTarget,data){
            // removing already open tool tip if it exists.

            var val = tipTarget.html();
            myDialog(tipTarget, {
                headerText: 'Clear Grade',
                message: data.message,
                buttons: [
                    {
                        text: 'Yes',
                        className: 'btn-delete',
                        click: function(e) {
                            sendRequest(data);
                        }
                    }, {
                        text: 'Cancel',
                        className: 'btn-cancel',
                        click: function(e) {
                            e.preventDefault();
                            $('.ssi-tip').remove();
                            $('.tip-container').remove();
                        }
                    }
                ]
            });
            $('.tip-container').addClass('r0');
            $("input.orderTopicToolTip").val(val);
            //            $('.ssi-tip').attr('style','top: 483px; left: 1048.5px;');
            //            var val = $("input.orderTopicToolTip").val();
        }

        // Returns the points in the form of a structured dataset
        function getPoints () {

            var data = { questionPoints: [], sectionPoints: {}, totalPoints: 0, obtainedPoints: 0, errors: [], finalComment: '' },
                    userId = $("#drpAssignmentStudent").val(),
                    assessmentAssignmentId = {{ $assignment->Id }},
                    assessmentId = {{ $assignment->assessmentId }}
                            finalComment = $('#assessment-comment').val();

            if ( finalComment === '' ) {
                // data.errors.push('Please provide the comment on assessment');
            };

            data.finalComment = finalComment;
            data.userId = userId;
            data.assessmentAssignmentId = assessmentAssignmentId;
            data.assessmentId = assessmentId;

            // Compiling the points for each of the Questions
            // Sections and Overall points.
            $('.achv-points').each(function(index, elem){

                var obj = {},
                        questionItem = $(elem).closest('.question-item');

                // Because for the essay questions, there is comment as well.
                if ( $(elem).hasClass('qessay-grade') ) {
                    obj.points = $(elem).val();
                    obj.questionComment = questionItem.find('.qessay-comment').val();

                    var lowerLimit = questionItem.find('.qessay-grade').data('lowerLimit'), upperLimit = questionItem.find('.qessay-grade').data('upperLimit'),
                            qNumber = questionItem.data('questionNumber');

                    if (( obj.points < lowerLimit ) || ( obj.points > upperLimit )) {
                        data.errors.push('Grade for Question#' + qNumber + ' must be between ' + lowerLimit + '-' + upperLimit );
                    }

                } else {
                    obj.points = $(elem).text();
                }

                obj.subsectionQuestionId = questionItem.data('subsectionQuestionId');
                obj.questionId = questionItem.data('questionId');
                obj.isCorrect = $(elem).data('isCorrect');

                // Fill up the question points.
                data.questionPoints.push(obj);


                // Compile the data for sections
                var sectionId = questionItem.closest('.section-answers').data('sectionId');
                var parentSectionId = questionItem.closest('.section-answers').data('parentSectionId');
                var netPoints = questionItem.find('.total-q-points').text();  // Total Question points.

                data.sectionPoints['section_' + sectionId] = data.sectionPoints['section_' + sectionId] || {};
                data.sectionPoints['section_' + sectionId]['sectionId'] = sectionId;
                data.sectionPoints['section_' + sectionId]['parentSectionId'] = parentSectionId;
                data.sectionPoints['section_' + sectionId]['points'] = data.sectionPoints['section_' + sectionId]['points'] || 0;
                data.sectionPoints['section_' + sectionId]['netPoints'] = data.sectionPoints['section_' + sectionId]['netPoints'] || 0;

                data.sectionPoints['section_' + sectionId]['points'] += parseFloat( obj.points ) || 0;
                data.sectionPoints['section_' + sectionId]['netPoints'] += parseFloat( netPoints ) || 0;

                // Put this sections point on this section.
                $('.section-answers[data-section-id="' + sectionId + '"]').data('sectionPoints', data.sectionPoints['section_' + sectionId]['points']);
                data.obtainedPoints += parseFloat( obj.points ) || 0;
                data.totalPoints    += parseFloat( netPoints ) || 0;
            });

            // Adding the data for parent sections
            $('.section-answers').each(function(index, elem) {
                var parentSectionId =  $(elem).data('parentSectionId'),
                        sectionId = $(elem).data('sectionId');

                if ( parentSectionId ) {

                    var points = data.sectionPoints['section_' + sectionId]['points'];
                    var netPoints = data.sectionPoints['section_' + sectionId]['netPoints'];

                    data.sectionPoints['section_' + parentSectionId] = data.sectionPoints['section_' + parentSectionId] || {};
                    data.sectionPoints['section_' + parentSectionId]['sectionId'] = parentSectionId;
                    data.sectionPoints['section_' + parentSectionId]['parentSectionId'] = 0;
                    data.sectionPoints['section_' + parentSectionId]['points'] = data.sectionPoints['section_' + parentSectionId]['points'] || 0;
                    data.sectionPoints['section_' + parentSectionId]['netPoints'] = data.sectionPoints['section_' + parentSectionId]['netPoints'] || 0;

                    data.sectionPoints['section_' + parentSectionId]['points'] += parseFloat( points ) || 0;
                    data.sectionPoints['section_' + parentSectionId]['netPoints'] += parseFloat( netPoints );
                };
            });


            // Jira# AAA-1962
            // For "Score Band Interim", calculate the points as such
            if ( $('#assmt_test_type').val() == 'Score Band Interim') {

                $('.section-answers').each(function(index, elem){
                    var sectionId = $(elem).data('sectionId');
                    var container = $('.section-answers[data-section-id="' + sectionId + '"]');
                    // data.sectionPoints['section_' + sectionId]['points'] = container.find('.achv-points[data-is-correct=Yes]').length / container.find('.achv-points').length;
                    data.sectionPoints['section_' + sectionId]['points'] = container.find('.achv-points[data-is-correct=Yes]').length;
                    data.sectionPoints['section_' + sectionId]['netPoints'] = container.find('.achv-points').length;
                });

                $('.section-answers').each(function(index, elem){
                    var parentSectionId =  $(elem).data('parentSectionId');

                    if ( parentSectionId ) {
                        var sectionId = parentSectionId;
                        var container = $('.section-answers[data-parent-section-id="' + sectionId + '"]');
                        // data.sectionPoints['section_' + sectionId]['points'] = container.find('.achv-points[data-is-correct=Yes]').length / container.find('.achv-points').length;
                        data.sectionPoints['section_' + sectionId]['points'] = container.find('.achv-points[data-is-correct=Yes]').length;
                        data.sectionPoints['section_' + sectionId]['netPoints'] = container.find('.achv-points').length;
                    }
                });
            };

            return data;
        }

        function saveGrading ( keepGoing ) {
            var points = getPoints();

            if ( points.errors.length !== 0 ) {
                showErrors( points.errors );

                return;
            };

            var jsonPoints = JSON.stringify( points );
            //alert(jsonPoints); //return false;
            $.ajax({
                url: '{{ route("saveStudentGrading") }}',
                method: 'post',
                data: {
                    jsonPoints: jsonPoints
                },
                beforeSend: function () {

                },
                success: function ( data ) {
                    //return false;
                    if ( keepGoing === true ) {
                        var nextOption = $('#drpAssignmentStudent option:selected').next('option');

                        nextUserId = '';
                        $.ajax({
                            url: '{{ route("getNextGradingStudent") }}',
                            method: 'post',
//                    dataType:text,
                            data: {assignmentId: {{$assignment->Id}}, studentId: $("#drpAssignmentStudent").val(), sectionId:{{$sectionId}} },
                            async: false,
                            beforeSend: function () {
                                // toggleMsg('Please wait');
                            },
                            success: function ( data ) {
                                nextUserId = data;
                                //console.log(nextUserId);
                            }
                        })


                        // Move the user to the next user's grading.
                        if ( nextUserId != '' ) {
//                    var nextUserId = nextOption.val();

                            var gradeStudent = "{{ route('gradeStudent', ['param'  => '#usid#'])}}";
                            var route = gradeStudent.replace('#usid#', "{{$assignment->Id}}" + '-' + "{{$sectionId}}" + '-' +nextUserId);

                            window.location = route;

                        } else {
                            var category = "{{ $assignment->assessment->category->Option }}";

                            if ( category == 'Fixed Form') {
                                window.location = "{{ route('studentGrading', ['type' => 'fixed-form', 'id' => $assignment->Id ]) }}";
                            } else {
                                window.location = "{{ route('studentGrading', ['type' => 'formative', 'id' => $assignment->Id ]) }}";
                            }
                        }
                    } else {
                        var category = "{{ $assignment->assessment->category->Option }}";
                        if ( category == 'Fixed Form') {
                            window.location = "{{ route('studentGrading', ['type' => 'fixed-form', 'id' => $assignment->Id ]) }}";
                        } else {
                            window.location = "{{ route('studentGrading', ['type' => 'formative', 'id' => $assignment->Id ]) }}";
                        }
                    }
                },
                complete: function () {
                    showMsg('User successfully graded.');
                }
            });
        }

        $(document).ready(function() {

            updateButtonBehaviour();
            hideMoveToQuestion();

            $('.essay-answer-details p').each(function(index, elem){
                if(
                        ($.trim($(elem).text()) == '') &&
                        ($.trim($(elem).attr('class')) == '') &&
                        ($.trim($(elem).attr('id')) == '') &&
                        ($.trim($(elem).attr('abbr')) == '')
                ) {
                    $(elem).remove();
                }
            });

            $(document).on('click', '.move-to-next', function ( e ) {
                e.preventDefault();

                var questions    = $('.essay-not-graded'),
                        self         = $(this);
                currentIndex = self.data('currentIndex');
                prevIndex = currentIndex;
                currentIndex = ( (currentIndex == undefined) || ( currentIndex > (questions.length - 1))) ? 0 : ++currentIndex;

                if ( currentIndex > ( questions.length - 1 )) {
                    currentIndex = 0;
                };

                if( prevIndex == undefined || $(questions[currentIndex]).closest('.question-item').find('.qessay-grade').val() == '')
                {
                    // showMsg('No more essay questions.');
                    self.data('currentIndex', currentIndex);
                    if ( questions.length !== 0 ) {
                        scrollToEl( questions[currentIndex] );
                    };
                } else {
                    $('.qessay-grade').each(function(index, elem) {
                        if ( $(elem).val() === '' ) {
                            scrollToEl( $(elem).closest('.question-item') );

                            return false;
                        }
                    });
                }
            });


            $(document).on('click','.clear',function(e){
                e.preventDefault;
                var data= {};
                data.url = $(this).data('url');
                data.assignmentId = "{{$assignmentId}}";
                data.userId       = [$('#drpAssignmentStudent').val()];
                data.sectionId    = "{{$sectionId}}";
                data.message = "Are you sure you want to clear the grading ?"
                data.comeFrom = "clear";
                data.tipTarget = $(this);
                mapTollTip($(this),data);
            });

            $(document).on('click', '#save-grading', function ( e ) {
                e.preventDefault();
                saveGrading();
            });

            $(document).on('click', '#save-continue-grading', function ( e ) {
                e.preventDefault();
                if ( $(this).val() === 'Save and Finish' ) {
                    saveGrading();
                } else {
                    saveGrading( true );
                }
            });

            previousStudentIndex = $("#drpAssignmentStudent")[0].selectedIndex;
            var c_index  = $("#drpAssignmentStudent").find('option:selected').index();
            $('#drpAssignmentStudent').confirmPopup({
                message: 'You may lose any unsaved changes. Continue?',
                confirmPopupCallOn:'change',
                onPositiveDecision: function(){
                    var n_index  = $("#drpAssignmentStudent").find('option:selected').index();
                    $.fancybox.close();
                    $('#drpAssignmentStudent')[0].sumo.selectItem(n_index);
                    $( ".options li" ).eq(n_index).css( "display", "none" );
                    $( ".options li" ).eq(c_index).css( "display", "block" );
                    c_index = n_index;

                    previousStudentIndex = $("#drpAssignmentStudent")[0].selectedIndex;
                    fetchGrading();
                },
                onNegativeDecision: function(){
                    $("#drpAssignmentStudent")[0].sumo.selectItem(previousStudentIndex);
                }
            });

            $('.cncl_link').confirmPopup({
                message: 'Are you sure you want to leave ?',
                onPositiveDecision: function(){
                    window.location.href =  $('#cancel-q-record').data('href');
                },
                onNegativeDecision: function(){
                }
            });

            // $('#drpAssignmentStudent').on('change', function () {

            // });

            $(document).on('keyup', '.achv-points', function(){
                calculateStats();
            });

            calculateStats();
        });
    </script>
@stop
