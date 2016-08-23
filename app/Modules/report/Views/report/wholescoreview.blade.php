

<table class="table table-bordered table-hover table-striped" id="wholescore">
 <thead>
    <tr>
        <th>Student Name</th>
        @foreach($subjects as $val)
        <th><i>{{$val->assignmentname}}</i></th>
        @endforeach
        {{--<th>Correct Questions</th>--}}
        {{--<th>Percentage(%)</th>--}}
    </tr>
    </thead>
    <tbody>
<?php $all_users_count=0; ?>
        @foreach($students as $student )
    <tr>
        <td>
        {{$student->name}}
        </td>
        <td>
            <?php
            $user_obtained=(($student->multi_answers_count * $marks->mcsingleanswerpoint)+($student->essay_answers_count * $marks->essayanswepoint));
            $user_obtained= $user_obtained - (($student->total_count - ($student->multi_answers_count +$student->essay_answers_count)) * $marks->guessing_panality);
            $all_users_count +=$user_obtained;
            ?>
{{$user_obtained}}
        </td>
       {{-- --}}{{--<td>--}}{{--
        --}}{{--{{$student->answers_count}}--}}{{--
        --}}{{--</td>--}}{{--
        --}}{{--<td>--}}{{--
        --}}{{--{{($student->answers_count/$student->total_count)*100}}%--}}{{--
        --}}{{--</td>--}}
    </tr>
        @endforeach

    </tbody>
</table>
@if(count($students)>0)
<table class="table average">
    <tr><td>
        
    <h4 align="middle"><i>  @foreach($subjects as $sub) {{$sub -> subject}}
   
    @endforeach
    <?php $score=$all_users_count/(count($students)) ?>
        :: {{$score}}</i></h4></td>
    </tr>
</table>
@endif
<div id="chart-1">FusionCharts XT will load here!</div>
<script src="{{asset('/js/fusion/js/fusioncharts.js') }}"></script>
<script type="text/javascript" src="{{asset('/js/fusion/js/themes/fusioncharts.theme.ocean.js')}}"></script>

<script type="text/javascript">
     FusionCharts.ready(function(){
        var salesChart = new FusionCharts({
            type: 'column2d',
            dataFormat: 'json',
            renderAt: 'chart-1',
            width: '950',
            height: '350',
            dataSource: {
                "chart": {
                    "caption": "Whole class score report",
                    "subCaption": "",
                    "xAxisName": "Subject Name",
                    "yAxisName": "Average Marks",
                    "numberPrefix": "",
                    "theme": "fint"
                },
                
               
                "dataset": [
                    {
                        "data": [
                               {
                                    'label':'{{$sub -> subject}}',
                                'value' : '{{$score}}'
                          }
                    ]
                    }
                ]
            }
        }).render();
    });
</script>