
	<!-- Scripts -->
	
	<script src="{{ asset('/js/bootstrap.min.js')}}"></script>
	
	<script src="{{ asset('/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
			<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="{{ asset('/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js')}}"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="{{ asset('/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5')}}"></script>
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5')}}" media="screen" />

	<script src="{{ asset('/js/jquery.form.js')}}"></script>

	<script type="text/javascript">
		// Only enable if the document has a long scroll bar
		// Note the window height + offset
		if ( ($(window).height() + 100) < $(document).height() ) {
		    $('#top-link-block').removeClass('hidden');
		}
		$(document).ready(function() {
			$('.fancybox').fancybox();
		    $('.datatableclass').DataTable({
		    	language: {
			        paginate: {
			            previous: 'Previous',
			            next:     'Next'
			        },
			        aria: {
			            paginate: {
			                previous: 'Previous',
			                next:     'Next'
			            }
			        }
			    }
		    });

		} );	
	</script>
	<script src="http://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>

	<script>
		$(function(){
			$('#lessonstable').DataTable({
				language: {
					paginate: {
						previous: 'Previous',
						next:     'Next'
					},
					aria: {
						paginate: {
							previous: 'Previous',
							next:     'Next'
						}
					}
				},
				columns: [
					{	name: 'Category',
						title: 'Category',
					},
					{
						name: 'Subject',
						title: 'Subject',
					},
					{
						title: 'Lesson',
					},
					{
						title: '',
					},
				],
				rowsGroup: [// Always the array (!) of the column-selectors in specified order to which rows groupping is applied
					// (column-selector could be any of specified in https://datatables.net/reference/type/column-selector)
					'Category:name',
					'Subject:name',
					0,1,2
 				],
				pageLength: '20',
			})
		});

 		$(function(){
			$('#subjectstable').DataTable({
				language: {
					paginate: {
						previous: 'Previous',
						next:     'Next'
					},
					aria: {
						paginate: {
							previous: 'Previous',
							next:     'Next'
						}
					}
				},
				columns: [
					{	name: 'Category',
						title: 'Category',
					},
					{
						name: 'Subject',
						title: 'Subject',
					},

					{
						title: '',
					},
				],
				rowsGroup: [// Always the array (!) of the column-selectors in specified order to which rows groupping is applied
					// (column-selector could be any of specified in https://datatables.net/reference/type/column-selector)
					'Category:name',
					'Subject:name',
					0,1,
 				],
				pageLength: '20',
			})
		});

	</script>

