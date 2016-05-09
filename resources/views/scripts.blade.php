
	<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	
	<script src="{{ asset('/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
	<script type="text/javascript">
		// Only enable if the document has a long scroll bar
		// Note the window height + offset
		if ( ($(window).height() + 100) < $(document).height() ) {
		    $('#top-link-block').removeClass('hidden');
		}
		$(document).ready(function() {
		    $('.datatableclass').DataTable();
		} );	
	</script>