@extends('default')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<ul class="nav nav-tabs" role="tablist">
			    <li><a href="{{ url('/resources/category') }}">Category</a></li>
		        <li><a href="{{ url('/resources/subject') }}">Subjects</a></li>
		        <li><a href="{{ url('/resources/lesson') }}">Lessons</a></li>
		        <li class="active"><a href="{{ url('/resources/question') }}">Questions</a></li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-heading">&nbsp;<!-- Lessons -->
					<a href="{{ url('/resources/questionadd/') }}" class="btn btn-primary btn-sm right" ><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
				</div>
				@if (count($errors) > 0)
					<div class="alert alert-danger">
						<strong>Whoops!</strong> There were some problems with your input.<br><br>
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
				<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
				<div class="panel-body">
				<div class="panel panel-default">
					<div class="panel-heading searchfilter pointer">Advanced Filters
						<a href="javascript:;"><span class="glyphicon glyphicon-chevron-up right " aria-hidden="true"></span></a>
					</div>
					<div class="panel-body searchfilter-body hide">	
					<div class="form-group col-md-6">
						<label class="col-md-2 control-label" >Institution</label>
						<div class="col-md-10">
							<select class="form-control" name="institution_id" id="institution_id" onchange="inst_change()">
								<option value="0">Select</option>
								@foreach($inst_arr as $id=>$val)
								<option name="institution_id" value="{{$id}}">{{ $val }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label class="col-md-2 control-label">Category</label>
						<div class="col-md-10">
							<select class="form-control" name="category_id" id="category" onchange="cat_change()">
								<option value="0">Select</option>
								
							</select>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label class="col-md-2 control-label">Subject</label>
						<div class="col-md-10">
							<select class="form-control" name="subject_id" id="subject" onchange="sub_change()">
								<option value="0">Select</option>
								
							</select>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label class="col-md-2 control-label">Lessons</label>
						<div class="col-md-10">
							<select class="form-control" name="lessons_id" id="lessons">
								<option value="0">Select</option>
								
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
					
					<div class="clearfix"></div>
					<table id="example" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Question Title</th>
				                <th>Question Type</th>
				                 <th>Question Passage</th>
				            </tr>
				        </thead>
				        <tbody id="question_list_filer">
				            @foreach( $list as $id => $value )
				            <tr>
				                <td>{{ $value['question_title'] }}</td>
				                 <td>{{ $value['question_type'] }}</td>
								 <td>{{ $value['passage_title'] }}</td>
				                <td>
				                	<a href="{{ url('/resources/questionedit/'.$value['qid']) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
									<a href="javascript:;" data-ref="{{ url('/resources/questiondel/'.$value['qid']) }}" class="btn btn-default btn-sm confirm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
{!! HTML::script(asset('/js/custom/confirm.js')) !!}
<script>
	function filter(){
		var csrf=$('Input#csrf_token').val();
		var institution_id=$('#institution_id').val();
		var category_id=$('#category_id').val();
		var subject_id=$('#subject_id').val();
		var lessons_id=$('#lessons_id').val();
		if(subject_id=='')subject_id=0;
		if(institution_id=='')institution_id=0;
		if(category_id=='')category_id=0;
		if(lessons_id=='')lessons_id=0;
		var data={'institution':institution_id,'category':category_id,'subject':subject_id,'lessons':lessons_id};
		var url="filter_data_question";
		ajax(url,data,csrf);
	}
	function ajax(url,data,csrf){
		$.ajax(
				{
					url:url,
					headers: {"X-CSRF-Token": csrf},
					type:"post",
					data:data,
					success:function(response){
						$('#question_list_filer').empty();
						var tr;
						for (var i = 0; i < response.length; i++) {
 							tr = $('<tr/>');
							tr.append("<td>" + response[i].question_title + "");
							tr.append("<td>" + response[i].question_type + "");
							tr.append("<td>" + response[i].passage_title + "");
							tr.append("<a href='questionedit/"+ response[i].qid +"' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></a>");
							tr.append("<a href='questiondel/"+ response[i].qid +"' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-trash'' aria-hidden='true'></span></a></td>");
 							$('#question_list_filer').append(tr);
						}
					}
				}
		);
	}

$( document ).ready(function() {
	$('.searchfilter').on("click",function(e){    	
    	//console.log('searchfilter ');
    	$(".searchfilter span")
        .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        $('.searchfilter-body').toggleClass('hide show');
    });
});    

function inst_change(){
            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {
                        headers: {"X-CSRF-Token": csrf},
                        url: '/assesmenttool/public/resources/categoryList/' + $('#institution_id').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                            $('#category').empty();
                            var opt = new Option('--Select Category--', '');
                            $('#category').append(opt);
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#category').append(opt);
                            }
                        }
                    }
            )
        }
function cat_change(){
            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {
                        headers: {"X-CSRF-Token": csrf},
                        url: '/assesmenttool/public/resources/subjectList/' + $('#category').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                            $('#subject').empty();
                            var opt = new Option('--Select Subject--', '');
                            $('#subject').append(opt);
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#subject').append(opt);
                            }
                        }
                    }
            )
        }
        function sub_change(){
            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {
                        headers: {"X-CSRF-Token": csrf},
                        url: '/assesmenttool/public/resources/lessonsList/' + $('#subject').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                            $('#lessons').empty();
                            var opt = new Option('--Select Lessons--', '');
                            $('#lessons').append(opt);
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#lessons').append(opt);
                            }
                        }
                    }
            )
        }

</script>
@endsection