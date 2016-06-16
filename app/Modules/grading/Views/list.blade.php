@extends('default')
@section('header-assets')
@parent
{!! HTML::script(asset('assets/js/common.js')) !!}
{!! HTML::script(asset('assets/js/grade.js')) !!}
@stop
@section('content')

<style>
.tip-btns ul.tip-btn-list li.btn-grade a {
    background: #777 none repeat scroll 0 0;
    border-radius: 3px;
    color: #fff;
    display: block;
    font-family: "droid_sansregular";
    font-size: 12px;
    height: 25px;
    line-height: 25px;
    padding: 0 10px;
    text-decoration: none;
}
.tip-btns ul.tip-btn-list li.btn-grade a {
    width: 120px !important;
}
.tip-btns ul.tip-btn-list li {
    margin: 1px !important;
}

.tip-btns ul.tip-btn-list {
    margin: 0 !important;
    text-align: center;
}
</style>
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
					Grading					
				</div>

				<div class="panel-body">				
					<label class="col-md-2 control-label">Institution</label>
					<div class="col-md-4">
						<select class="form-control" name="institution_id" id="institution_id">
							<option value="0">Select</option>
							@foreach($inst_arr as $id=>$val)
							<option value="{{ $id }}">{{ $val }}</option>
							@endforeach
						</select>
					</div>				
				</div>	

				<div class="panel-body">
					<table id="assignmentstable" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Assessment Name</th>
				                <th>Assignment Name</th>
				                <th>Action</th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach($assignments as $id => $asn )
				            <tr>				                
				                <td>{{ $asn->assessment_name }}</td>
				                <td>{{ $asn->assignment_name }}</td>
				                <td>
				                	<!-- <a href="{{ url('/resources/assignmentedit/'.$id) }}" class="btn btn-default btn-sm" title="Grade">
				                	<span class="glyphicon glyphicon-education" aria-hidden="true"></span>
				                	</a>
 -->
				                	<i class="icons ico-grade"  id="grade"  formative-url="{{route('studentGrading',array('id'=>$asn->assignmentId))}}" question-url="{{route('questionGrading',array('id'=>$asn->assignmentId."-".$asn->assessmentId))}}" >
			                             <span class="reply_box">
			                                Grade
			                            </span>
			                         </i>		

								</td>
				            </tr>
				            @endforeach				            
				        </tbody>
				    </table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection