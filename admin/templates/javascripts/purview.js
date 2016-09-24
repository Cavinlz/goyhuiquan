define(
function() {'use strict'
	
	var active = [];
	
	return {
		run:function(){
			$(document).delegate('li[id^=tab-]', 'click', function() {
				var key = $(this).attr('id').split('-')[1];
				
				if(active[key]== undefined || active[key] != true)
				 $.ajax({
	                    url: $.return_url({ctrl:'purviews',action:'shwtree'}),
	                    data:{r:key},
	                    method:'post',
	                    dataType: 'html',
	                    success: function(html) {
	                    	
	                    	$('#role-'+key).html(html);
	                        active[key] = true;
	                       
	                    },
	                    error: function(xhr, ajaxOptions, thrownError) {
	                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	                    }
	                });
			});
		}
	}

});