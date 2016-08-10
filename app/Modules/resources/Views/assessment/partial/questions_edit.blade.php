<ul class="nav nav-tabs">
	<link rel="stylesheet" href="{{asset('css/bootstrap-multiselect.css')}}" type="text/css">

	<script type="text/javascript" src="{{asset('js/bootstrap-multiselect.js')}}"></script>
	<li class="tab active"><a id="questions_tab" data-toggle="tab" data-tab='question-holder' href="#questions">Questions</a></li>
	<li class="tab"><a id="passages_tab" data-toggle="tab" data-tab='passage-holder' href="#passages">Passages</a></li>
</ul>

<div class="tab-content">
	<div class="panel panel-default">
		<div class="pull-right">
			<button type="button" class="btn btn-danger" id="filters_show">show filters</button>
		</div>
		<div class="edit_assessment">
		<div class="panel-heading searchfilter pointer">Filters
			{{--<a href="javascript:;"><span class="glyphicon glyphicon-chevron-up right " aria-hidden="true"></span></a>--}}
		</div>
		<div class="panel-body searchfilter-body">
			<div class="form-group col-md-6 required" >
				<label class="col-md-2 control-label" >Institution</label>
				<div class="col-md-10">
					<select class="form-control" name="institution_id" id="institution_id" onchange="change_institution('question')">
						<option value="0">--Select Institution--</option>
						@foreach($inst_arr as $id=>$val)
							<option value="{{ $id }}" {{ ($id == $institution_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group col-md-6 required">
				<label class="col-md-2 control-label">Category</label>
				<div class="col-md-10">
					<select class="form-control" name="category_id" id="category_id" onchange="change_category('question')">
						<option value="0">--Select Category--</option>
						@foreach($category as $id=>$val)
							<option value="{{ $id }}" >{{ $val }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group col-md-6 required">
				<label class="col-md-2 control-label">Subject</label>
				<div class="col-md-10">
					<select class="form-control" name="subject_id[]" id="subject_id"  class="multipleSelect" multiple="multiple" onchange="change_lessons('question')">
						{{--<option value="0">--Select Subject--</option>--}}
						@foreach($subjects as $id=>$val)
							<option value="{{ $id }}">{{ $val }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group col-md-6 ">
				<label class="col-md-2 control-label">Lessons</label>
				<div class="col-md-10">
					<select class="form-control" name="lessons_id[]" id="lessons_id" multiple onchange="change_question_type('question')">
						{{--<option value="0">--Select Lessons--</option>--}}
						{{--@foreach($lesson as $id=>$val)--}}
							{{--<option value="{{ $id }}" {{($id == $lessons_id)? 'selected = "selected"' : '' }}>{{ $val }}</option>--}}
						{{--@endforeach--}}
					</select>
				</div>
			</div>
			<div id="question_type_div" class="form-group col-md-6 required">
				<label class="col-md-2 control-label">Question Type</label>
				<div  class="col-md-10">
					<select class="form-control" name="question_type" id="question_type" onchange="filter()">
						{{--<option value="0">--Select Question Type--</option>--}}
						{{--@foreach($questiontype as $id=>$val)--}}
							{{--<option value="{{ $id }}" {{($id == $question_type_id)? 'selected = "selected"' : '' }}>{{ $val }}</option>--}}
						{{--@endforeach--}}
					</select>
				</div>
			</div>
			<div class="form-group col-md-10">
			</div>
			<!-- <div class="form-group col-md-2">
                <div class="col-md-6">
                    <div class="move-arrow-box">
                        <a class="btn btn-primary" onclick="filter();" href="javascript:;">Apply Filter</a>
                    </div>
                </div>
            </div> -->
		</div>
		</div>
	</div>

	<div id="questions" class="tab-pane fade in active">
		<h3>Questions</h3>

		<table id="question_table" class="table table-striped table-bordered   parent-grid" cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input type="checkbox" id="" value="" class="check-all-question"></th>
				<th>Question Name</th>

			</tr>
			</thead>
			<tbody  id="questions-list">
			{{--@foreach( $question_title_remove_ids as $name )--}}
				{{--<tr>--}}
					{{--<td>--}}
						{{--<input type="checkbox" id="questions-list"  value="{{ $name['id'] }}"  class="assess_qst check-question" data-group-cls="btn-group-sm">--}}
					{{--</td>--}}
					{{--<td>{{ $name['title'] }}</td>--}}

				{{--</tr>--}}
			{{--@endforeach--}}
			</tbody>
		</table>

		<div class="form-group">
			<div class="col-md-4">
				<div class="move-arrow-box">
					<a class="btn btn-primary" onclick='addOrRemoveInGrid(this, "add");' href="javascript:;">Add Questions</a>
				</div>
			</div>
		</div>
		<b>{{"Selected Questions"}}</b>
		<table id="selected-questions" class="table table-striped table-bordered   parent-selected-grid" cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input type="checkbox" name="" class="check-all-selected-question" value=""></th>
				<th>Question Name</th>
			</tr>
			</thead>
			<tbody class="child-grid">
			<?php
			if (count($errors) > 0){
			if(old('QuestionIds'))
				$old_values=old('QuestionIds');
			else{
				$old_values=array();
			}
			foreach($old_values as $value){
			?>
			<input type="hidden" name="QuestionIds[]" id="QuestionIds" value="<?php echo $value;?>">
			<?php
			}
			}else{
			?>
			@foreach( $questions_lists as $name )
				<tr>
					<td>
						<input type="checkbox" id="questions-list"  value="{{ $name['id'] }}"  class="assess_qst check-selected-question" data-group-cls="btn-group-sm">
						<input type="hidden" name="QuestionIds[]" id="QuestionIds" value="{{ $name['id'] }}">
						<script> if($.inArray('{{$name["subject_id"]}}',subjects_list) == -1){
								subjects_list.push('{{$name["subject_id"]}}');
							}
							if($.inArray('{{$name["lesson_id"]}}',lessons_list) == -1){
								lessons_list.push('{{$name["lesson_id"]}}');
							}
						//	lessons_list=[];
						</script>
					</td>
					<td>{{ $name['title'] }}</td>

				</tr>
			@endforeach
			<?php }
			?>

			</tbody>
		</table>
		<div class="form-group">
			<div class="col-md-4">
				<div class="move-arrow-box">
					<a class="btn btn-primary" onclick='addOrRemoveInGrid(this, "remove");' href="javascript:;">Remove question</a>
				</div>
			</div>
		</div>
	</div>
	<div id="passages" class="tab-pane fade">
		<h3>Passages</h3>
				<table id="passage_table" class="table table-striped table-bordered assess  parent-grid" cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input type="checkbox" name="" id="" value="" class="check-all-passage"></th>
				<th>Passage Name</th>

			</tr>
			</thead>
			<tbody id="passages-list">
			{{--@foreach( $passages_list_not as $id => $name )--}}
				{{--<tr>--}}
					{{--<td>--}}
						{{--<input type="checkbox" name="" id="passages-list"  value="{{ $name['id'] }}"  class="assess_qst check-passage" data-group-cls="btn-group-sm">--}}
					{{--</td>--}}
					{{--<td>{{ $name['title'] }}</td>--}}

				{{--</tr>--}}
			{{--@endforeach--}}
			</tbody>
		</table>

		<div class="form-group">
			<div class="col-md-2">
				<button type="button" class="btn btn-primary" onclick='addOrRemoveInGrid(this, "add");' href="javascript:;">
					Add passage
				</button>
			</div>


			<div class="clearfix"></div>
			<b>{{"Selected Passages"}}</b>
			<table id="selected-passage" class="table table-striped table-bordered   parent-selected-grid" cellspacing="0" width="100%">
				<thead>
				<tr>
					<th><input type="checkbox" name="" class="check-all-selected-passage" value=""></th>
					<th>Passage Name</th>
				</tr>
				</thead>
				<?php
				if (count($errors) > 0){
				if(old('QuestionIds'))
					$old_values=old('QuestionIds');
				else{
					$old_values=array();
				}
				foreach($old_values as $value){
				?>
				<input type="hidden" name="passageIds[]" id="passageIds" value="<?php echo $value;?>">
				<?php
				}
				}else{?>
				@foreach( $passages_lists as $id => $name )
					<tr>
						<td>
							<input type="checkbox" name="" id="passages-list"  value="{{ $name['id'] }}"  class="assess_qst check-selected-passage" data-group-cls="btn-group-sm">
							<input type="hidden" name="passageIds[]" id="passageIds" value="{{ $name['id'] }}">
						</td>
						<td>{{ $name['title'] }}</td>

					</tr>
				@endforeach
				<?php }
				?>
				<tbody class="child-grid">
				</tbody>
			</table>

			<div class="form-group">
				<div class="col-md-4">
					<div class="move-arrow-box">
						<a class="btn btn-primary" onclick='addOrRemoveInGrid(this, "remove");' href="javascript:;">Remove Passage</a>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		//alert(JSON.stringify(subjects_list));
		$('#question_table').DataTable( {

			"scrollY":        "850px",
			"scrollCollapse": true,
			"paging":         true
		} );
		$('#passage_table').DataTable( {

			"scrollY":        "850px",
			"scrollCollapse": true,
			"paging":         true
		} );
		$('#selected-questions').DataTable( {

			"scrollY":        "850px",
			"scrollCollapse": true,
			"paging":         true
		} );
		$('#selected-passage').DataTable( {

			"scrollY":        "850px",
			"scrollCollapse": true,
			"paging":         true
		} );
		{{--//$('#subject_id').val({{$subject_id}});--}}
		{{--var a={{$subject_id}};--}}
		// alert(a);
		//alert($('#subject_id').val())
		$('.edit_assessment').hide();
	} );
	$('#subject_id').multiselect();
	 $('#lessons_id').multiselect();
	$('#passage_subject_id').multiselect();
	$('#filters_show').on('click',function(){
		$('#filters_show').hide();
		$('.edit_assessment').show();
	});
	$('#institution_id').val({{$institution_id}});
	$('#category_id').val({{$category_id}});
	$('#questions_tab').on('click',function(){
			$('#question_type_div').show();
		});

// 		for hiding the question type filter
		$('#passages_tab').on('click',function(){
			$('#question_type_div').hide();
		});
	// $('#passage_lessons_id').multiselect();
</script>

@include('resources::assessment.assessment_js_validation')



