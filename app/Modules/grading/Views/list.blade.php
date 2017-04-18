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
					<!-- <a  href="#bulk_import" class="btn btn-sm btn-primary pull-right">Bulk import Grades</a>	 -->	

					<a href="{{ route('gradesBulkImport') }}" class="btn btn-primary btn-sm right fancybox fancybox.ajax"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Bulk Upload</a>	
				</div>

                <?php   $sessRole = getRole() ;
                if($sessRole == 'administrator'){?>
				<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">

                <div class="panel-body">
					<label class="col-md-2 control-label">Institution</label>
					<div class="col-md-4">
						<select class="form-control" name="institution_id" id="institution_id" onchange="getAssignmentsforgrading()">
							<option value="0">--All--</option>
							@foreach($inst_arr as $id=>$val)
							<option value="{{ $id }}">{{ $val }}</option>
							@endforeach
						</select>
					</div>				
				</div>
                <?php }?>


				<div class="panel-body">
				 <table id="grade_table" class="table table-striped table-bordered " cellspacing="0" width="100%">


				        <thead>
				            <tr>
				                <th>Assessment Name</th>
				                <th>Assignment Name</th>
				                <th>Status</th>
				                
				                @if(Auth::user()->role_id == 3)
				                <th>Action</th>
				                @endif
				            </tr>
				        </thead>
				        <tbody id="assignbody">
				       
				            @foreach($assignments as $id => $asn )
				            <?php if(!isset($grading_status[1][$asn->assignmentId])){
								$grading_status[1][$asn->assignmentId]=0;
								}
				            	?>
				            <tr>				                
				                <td>{{ $asn->assessment_name }}</td>
				                <td>{{ $asn->assignment_name }}</td>
				                <td>
				             
					                @if($grading_status[1][$asn->assignmentId]==0)
					                	notstarted
					                @elseif($grading_status[0][$asn->assignmentId]==$grading_status[1][$asn->assignmentId])
					                	completed
					                @else
					                	inprogress
					                @endif

				                </td>
				                @if(Auth::user()->role_id == 3)
				                <td>
				                <!-- 	<a href="{{ url('/resources/assignmentedit/'.$id) }}" class="btn btn-default btn-sm" title="Grade">
				                	<span class="glyphicon glyphicon-education" aria-hidden="true"></span>
				                	</a> -->
                             
				                	<i class="icons ico-grade"  id="grade"  formative-url="{{route('studentGrading',array('id'=>$asn->assignmentId,$asn->assessmentId))}}" question-url="{{route('questionGrading',array('id'=>$asn->assignmentId,$asn->assessmentId))}}" >
			                             <span class="reply_box">
			                                Grade
			                            </span>
			                         </i>
                                

								</td>
								@endif
				            </tr>
				            @endforeach		
				                       
				        </tbody>
				    </table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="bulk_import">

</div>

	<script>
	$(document).ready(function() {
    $('#grade_table').DataTable({
	aoColumnDefs: [
  {
     bSortable: false,
     aTargets: [ -1 ]
  }
]
 });
});
	$('#grade_table').dataTable({
    "bPaginate": false,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": true,
    order: [],
	columnDefs: [ { orderable: false, targets: [0] } ],
    "bAutoWidth": false });

		function getAssignmentsforgrading(){
			var csrf=$('Input#csrf_token').val();
			 var loadurl = "{{ url('/resources/assignments') }}/" ;

			$.ajax(
					{

						headers: {"X-CSRF-Token": csrf},
						url:loadurl + $('#institution_id').val(),
						type: 'get',
						success: function (response) {
							  $('#grade_table').dataTable().fnDestroy();
							$('#assignbody').empty();
							var tr;
							for (var i = 0; i < response.length; i++) {
								tr = $('<tr/>');
								tr.append("<td>" + response[i].assessment_name + "");
								tr.append("<td>" + response[i].name + "");
								tr.append("<td>" + response[i].gradestatus + "");
								@if(Auth::user()->role_id == 3)
								tr.append("<td>"+ "</td>");
								@endif
								$('#assignbody').append(tr);

							}
							  $('#grade_table').dataTable();
						}
					});

		}
	</script>
	{!! HTML::script(asset('/js/custom/confirm.js')) !!}
	
@endsection