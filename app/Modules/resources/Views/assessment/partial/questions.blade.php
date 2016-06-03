<ul class="nav nav-tabs">
  <li class="tab active"><a data-toggle="tab" data-tab='question-holder' href="#questions">Questions</a></li>
  <li class="tab"><a data-toggle="tab" data-tab='passage-holder' href="#passages">Passages</a></li>
</ul>

<div class="tab-content">
  <div id="questions" class="tab-pane fade in active">
    <h3>Questions</h3>
		<table id="example" class="table table-striped table-bordered datatableclass parent-grid" cellspacing="0" width="100%">
		    <thead>
		        <tr>
		        	<th><input type="checkbox" id="" value="" class="check-all-question"></th>
		            <th>Name</th>

		        </tr>
		    </thead>
		    <tbody id="questions-list">
			@foreach( $inst_questions_list as $id => $name )
		        <tr>
		        	<td>
		        		<input type="checkbox" id="questions-list"  value="{{ $id }}"  class="assess_qst check-question" data-group-cls="btn-group-sm">
 					</td>
		            <td>{{ $name['title'] }}</td>

		        </tr>
			@endforeach
		    </tbody>
		</table>

		<div class="form-group">
			<div class="col-md-4">
				<div class="move-arrow-box">
					<a class="btn btn-primary" onclick='addOrRemoveInGrid(this, "add");' href="javascript:;">Add</a>
 				</div>
			</div>
		</div>
  </div>
  <div id="passages" class="tab-pane fade">
    <h3>Passages</h3>
    	<table id="example" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
		    <thead>
		        <tr>
		        	<th><input type="checkbox" name="" id="" value="" class="check-all-passage"></th>
		            <th>Name</th>

		        </tr>
		    </thead>
		    <tbody>
		        @foreach( $questions as $id => $name )
		        <tr>
		        	<td>
		        		<input type="checkbox" name="" id="" value="" class="assess_qst" data-group-cls="btn-group-sm">
		        	</td>
		            <td>{{ $name }}</td>

		        </tr>
		        @endforeach
		    </tbody>
		</table>

		<div class="form-group">
			<div class="col-md-2">
				<button type="button" class="btn btn-primary">
					Add Passage(s)
				</button>
				<div class="move-arrow-box">
					<a class="create_new_btn mR0 mt0 arrow-down" onclick='addOrRemoveInGrid(this, "add");' href="javascript:;">Add</a>
					<a class="create_new_btn mR0 mt0 arrow-top" onclick='addOrRemoveInGrid(this, "remove");' href="javascript:;">Remove</a>
				</div>
			</div>
		</div>

  </div>
</div>



<div class="clearfix"></div>
<b>{{"Selected Questions"}}</b>
<table id="selected-questions" class="table table-striped table-bordered datatableclass parent-selected-grid" cellspacing="0" width="100%">
    <thead>
        <tr>
        	<th><input type="checkbox" name="" class="check-all-selected-question" value=""></th>
            <th>Name</th>
        </tr>
    </thead>
	<?php
 	if (count($errors) > 0){
		 $old_values=old('QuestionIds');
		foreach($old_values as $value){
   	?>
	<input type="hidden" name="QuestionIds[]" id="QuestionIds" value="<?php echo $value;?>">
 	<?php
		}
	}
 	?>
    <tbody class="child-grid">
 	</tbody>
</table>
<div class="form-group">
	<div class="col-md-4">
		<div class="move-arrow-box">
 			<a class="btn btn-primary" onclick='addOrRemoveInGrid(this, "remove");' href="javascript:;">Remove</a>
		</div>
	</div>
</div>
