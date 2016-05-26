
						function do_something() {
							
							console.info( arguments );
						}

						function fancyConfirm(msg,callbackYes,callbackNo) {
							var ret;

							jQuery.fancybox({
								'modal' : true,
								'content' : "<div style=\"margin:1px;width:240px;\">"+msg+"<div style=\"text-align:center;margin-top:10px;\"><input id=\"fancyConfirm_ok\" style=\"margin:10px;padding:5px 25px;\" type=\"button\" value=\"Ok\"><input id=\"fancyconfirm_cancel\" style=\"margin:10px;padding:5px 10px;\" type=\"button\" value=\"Cancel\"></div></div>",
								'beforeShow' : function() {
									jQuery("#fancyconfirm_cancel").click(function() {
										$.fancybox.close();

										callbackNo();

									});

									jQuery("#fancyConfirm_ok").click(function() {
										$.fancybox.close();

										callbackYes();
									});
								}
							});
						}

						$(document).ready(function() {
							$(".confirm").click(function() {
								var url=$(this).attr('data-ref');
			//alert(url);
			fancyConfirm('Are you sure you want to delete?', function() {
				//do_something('yes');
				window.location=url;
								}, function() {
									do_something('no');
								});
							});
						});
					