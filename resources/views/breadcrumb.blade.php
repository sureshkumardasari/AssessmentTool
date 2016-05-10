	<?php
	//dd($breadcrumbLinks);
	?>
	<ol class="breadcrumb">
		@if( !empty($breadcrumbLinks['postfix']) )
			@foreach($breadcrumbLinks['postfix'] as $link => $label)
				@if (is_array($label) && isset($label['label']))
					<li><a href="{{ ($link != NULL) ? route($link) : 'javascript:;' }}">{{$label['label']}}</a></li>
				@else
					<li><a href="{{ ($link != NULL) ? route($link) : 'javascript:;' }}">{{$label}}</a></li>
				@endif
			@endforeach
		@endif
		<li> {{ $breadcrumbLinks['displayName'] }}</li>
	</ol>