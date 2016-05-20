<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#questions">Questions</a></li>
  <li><a data-toggle="tab" href="#passages">Passages</a></li>
</ul>

<div class="tab-content">
  <div id="questions" class="tab-pane fade in active">
    <h3>Questions</h3>
		<table id="example" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
		    <thead>
		        <tr>
		        	<th><input type="checkbox" name="" id="" value=""></th>
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
					Add Question(s)
				</button>
			</div>
		</div>
  </div>
  <div id="passages" class="tab-pane fade">
    <h3>Passages</h3>
    	<table id="example" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
		    <thead>
		        <tr>
		        	<th><input type="checkbox" name="" id="" value=""></th>
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
			</div>
		</div>

  </div>
</div>



<div class="clearfix"></div>
<b>{{"Selected Questions"}}</b>
<table id="example" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
    <thead>
        <tr>
        	<th><input type="checkbox" name="" id="" value=""></th>
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

