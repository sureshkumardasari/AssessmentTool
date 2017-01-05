	<?php
	//dd($breadcrumbLinks);
	$params=[];
	if($currentRoute == "studentQuestion"){
		$params['id'] = $current_params['assignment_id'];
		$params['assessment_id'] = $current_params['assessment_id'];
	}
	?>
	<ol class="breadcrumb">
		@if( !empty($breadcrumbLinks['postfix']) )
			@foreach($breadcrumbLinks['postfix'] as $link => $label)
				@if (is_array($label) && isset($label['label']))
					<li><a href="{{ ($link != NULL) ? route($link) : 'javascript:;' }}">{{$label['label']}}</a></li>
				@else
					<li><a href="{{ ($link != NULL) ? route($link,$params ) : 'javascript:;' }}">{{$label}}</a></li>
				@endif
			@endforeach
		@endif
		<li> {{ $breadcrumbLinks['displayName'] }}</li>
	</ol>