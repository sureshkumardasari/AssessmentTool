2<ul class="nav nav-tabs">
  <li class="tab active"><a data-toggle="tab" data-tab='question-holder' href="#questions">Questions</a></li>
  <li class="tab"><a data-toggle="tab" data-tab='passage-holder' href="#passages">Passages</a></li>
</ul>

<div class="tab-content">
  <div id="questions" class="tab-pane fade in active">
    <h3>Questions</h3>


<div class="panel panel-default">
					<div class="panel-heading searchfilter pointer">Advanced Filters
						<a href="javascript:;"><span class="glyphicon glyphicon-chevron-up right " aria-hidden="true"></span></a>
					</div>
					<div class="panel-body searchfilter-body hide">	
					<div class="form-group col-md-6">
						<label class="col-md-2 control-label" >Institution</label>
						<div class="col-md-10">
							<select class="form-control" name="institution_id" id="institution_id" onchange="change_institution()">
								<option value="0">--Select Institution--</option>
								@foreach($inst_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $institution_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label class="col-md-2 control-label">Category</label>
						<div class="col-md-10">
							<select class="form-control" name="category_id" id="category_id" onchange="change_category()">
								<option value="0">--Select Category--</option>
							</select>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label class="col-md-2 control-label">Subject</label>
						<div class="col-md-10">
							<select class="form-control" name="subject_id" id="subject_id" onchange="change_lessons()">
								<option value="0">--Select Subject--</option>
							</select>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label class="col-md-2 control-label">Lessons</label>
						<div class="col-md-10">
							<select class="form-control" name="lessons_id" id="lessons_id" onchange="change_question_type()">
								<option value="0">--Select Lessons--</option>
							</select>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label class="col-md-2 control-label">Question Type</label>
						<div class="col-md-10">
							<select class="form-control" name="question_type" id="question_type">
								<option value="0">--Select Question Type--</option>
							</select>
						</div>
					</div>
					<div class="form-group col-md-10">
					</div>
					<div class="form-group col-md-2">
						<div class="col-md-6">
							<div class="move-arrow-box">
								<a class="btn btn-primary" onclick="filter();" href="javascript:;">Apply Filter</a>
							</div>
						</div>
					</div>
					</div>
				</div>

		<table id="example" class="table table-striped table-bordered datatableclass  parent-grid" cellspacing="0" width="100%">
		    <thead>
		        <tr>
		        	<th><input type="checkbox" id="" value="" class="check-all-question"></th>
		            <th>Question Name</th>
		            
		        </tr>
		    </thead>
		    <tbody  id="questions-list">
		        @foreach( $question_title_remove_ids as $name )
		        <tr>	
		        	<td>
		        		<input type="checkbox" id="questions-list"  value="{{ $name['id'] }}"  class="assess_qst check-question" data-group-cls="btn-group-sm">
 					</td>
		            <td>{{ $name['title'] }}</td>
		            
		        </tr>
		        @endforeach				            
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
	  <table id="selected-questions" class="table table-striped table-bordered datatableclass  parent-selected-grid" cellspacing="0" width="100%">
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
		<table id="example" class="table table-striped table-bordered datatableclass parent-grid" cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input type="checkbox" name="" id="" value="" class="check-all-passage"></th>
				<th>Passage Name</th>

			</tr>
			</thead>
			<tbody>
			@foreach( $passages_list_not as $id => $name )
				<tr>
					<td>
						<input type="checkbox" name="" id="passages-list"  value="{{ $name['id'] }}"  class="assess_qst check-passage" data-group-cls="btn-group-sm">
					</td>
					<td>{{ $name['title'] }}</td>

				</tr>
			@endforeach
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
			<table id="selected-passage" class="table table-striped table-bordered datatableclass  parent-selected-grid" cellspacing="0" width="100%">
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
    $('#selected-passage').DataTable( {

        "scrollY":        "850px",
        "scrollCollapse": true,
        "paging":         true
    } );
    $('#passages').DataTable( {

        "scrollY":        "850px",
        "scrollCollapse": true,
        "paging":         true
    } );
    $('#selected-questions').DataTable( {

        "scrollY":        "850px",
        "scrollCollapse": true,
        "paging":         true
    } );
    $('#example').DataTable( {

        "scrollY":        "850px",
        "scrollCollapse": true,
        "paging":         true
    } );
} );
</script>

@include('resources::assessment.assessment_js_validation')
