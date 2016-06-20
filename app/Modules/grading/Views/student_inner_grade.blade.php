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



        <h1 class="fltL"></h1>
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
                            @foreach($user_list_detail as $id=>$val)
                                <option value="{{ $val->id }}">{{ $val->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fltL mt8">
                        Institution:
                         <span class="institutions">

				        </span>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="mL20 pb10">

                    <span class="txt_17_b w140 fltL">Date Taken:</span><span class="date-taken"></span>
                    <div class="clr"></div>
                </div>
                <div class="mL20">

                    <span class="txt_17_b w140 fltL">Date Graded:</span><span class="date-graded"></span>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>


    <div class="panel-body">
        <table  width="100%">
            <tbody>
            <?php
            $ans_arr = ['A', 'B', 'C', 'D', 'E'];
            ?>
            @foreach($questionss_list as $k=>$ass_qst)
             <tr>
                <td><b>Q. {{$ass_qst['Title']}}</b></td>
            </tr>

            <tr>
                <td>{{$ass_qst['ans_text']}}</td>
            </tr>
            {{--*/ $i = 0 /*--}}
            @foreach($ass_qst['answers'] as $idx => $a )
                {{--*/
                $ans_label = 'default';
                if($a['is_correct']=='YES')$ans_label = 'success' ;
                /*--}}
                <tr>
                    <td>
                        {{$ans_arr[$i]}}. <span class="label label-{{$ans_label}}">{{$a['ans_text']}}</span>
                    </td>

                </tr>
                {{--*/ $i++ /*--}}
            @endforeach
             <tr>
             <td>
                 <button type="button" class="btn btn-info btn-sm open-modal" data-toggle="modal" value="{{$ass_qst['Id']}}" data-target="#myModal">Edit</button>
             </td>
             </tr>
            @endforeach
            <tr>
                <td>


                    <!-- Modal -->
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">{{$ass_qst['Title']}} </h4>
                                </div>
                                <div class="modal-body">
                                    <p>Q. {{$ass_qst['ans_text']}}</p>
                                    {{--*/ $i = 0 /*--}}
                                    @foreach($ass_qst['answers'] as $idx => $a )
                                        <div>
                                            @if(($ass_qst['question_type'])=="Multiple Choice - Single Answer")
                                                <input type="radio" name="ans_val" id="ans_val" value="{{$a['Id']}}">
                                            @elseif(($ass_qst['question_type'])=="Multiple Choice - Multi Answer")
                                                <input type="checkbox" name="ans_val[]" id="ans_val" value="{{$a['Id']}}">
                                            @endif

                                            {{--*/
                                            $ans_label = 'default';
                                            if($a['is_correct']=='YES')$ans_label = 'success' ;
                                            /*--}}


                                            {{$ans_arr[$i]}}.
                                            <span class="label label-{{$ans_label}}">{{$a['ans_text']}}</span>

                                        </div>
                                        {{--*/ $i++ /*--}}
                                    @endforeach

                                    <div>

                                    </div>

                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Modal end -->
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" id="" data-target="#myModal">Save</button><button type="button" class="btn btn-info btn-sm" data-toggle="modal" id="" data-target="#myModal">Save and Grade</button>
    <div class="clr"></div>
</section>
@include('resources::grading.grading_js')

@endsection
@section('footer-assets')
@parent
 @stop
