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
					<a class="btn btn-primary" onclick='addOrRemoveInGrid(this, "add");' href="javascript:;">Add Question</a>
 				</div>
			</div>
		</div>
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
		            <th>Name</th>

		        </tr>
		    </thead>
		    <tbody>
		        @foreach( $inst_passages_list as $id => $name )
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
			<table id="selected-passage" class="table table-striped table-bordered datatableclass parent-selected-grid" cellspacing="0" width="100%">
				<thead>
				<tr>
					<th><input type="checkbox" name="" class="check-all-selected-passage" value=""></th>
					<th>Name</th>
				</tr>
				</thead>
				<?php
				if (count($errors) > 0){
				$old_values=old('passageIds');
				foreach($old_values as $value){
				?>
				<input type="hidden" name="passageIds[]" id="passageIds" value="<?php echo $value;?>">
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
						<a class="btn btn-primary" onclick='addOrRemoveInGrid(this, "remove");' href="javascript:;">Remove Passage</a>
					</div>
				</div>
			</div>
		</div>

  </div>
</div>



