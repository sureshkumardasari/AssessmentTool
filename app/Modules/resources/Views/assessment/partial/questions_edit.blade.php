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
		    <tbody>
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
    <tbody class="child-grid">
	@foreach( $question_tilte_details as $name )
		<tr>
			<td>
				<input type="checkbox" name="" id="" value="" class="assess_qst check-selected-question" data-group-cls="btn-group-sm">
			</td>
			<td>{{ $name['title'] }}</td>
			<input type="hidden" name="QuestionIds[]" value="{{ $name['id'] }}">
 		</tr>
	@endforeach

	</tbody>
</table>
<div class="form-group">
	<div class="col-md-4">
		<div class="move-arrow-box">
 			<a class="btn btn-primary" onclick='addOrRemoveInGrid(this, "remove");' href="javascript:;">Remove</a>
		</div>
	</div>
</div>
