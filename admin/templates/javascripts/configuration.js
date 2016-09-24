define(['bst'],function(){ 'use strict'
	
	return {
		run:function(){
			var cache = new Array();
			$(document).delegate('a[id^=tab_]','click',function() {
				var key = $(this).data('key');
				var flag = $(this).attr('id');
				var nextdiv = '#collapse-option-'+flag.split('-')[2];
				var parentobj = $('#parent-config');
				

				if($.inArray(flag, cache) === -1)
				    $.ajax({
				        url: $.return_url({ctrl:'configuration',action:'getconfig'}),
				        type: 'post',
				        data: {k:key},
				        dataType: 'html',
				        success: function(html) {
				        	  cache.push(flag);
				        	  
				        	  $(nextdiv+' .panel-body').html(html);
		                      
				        	  
				        	  $('.padd').children().attr('class','tab-pane fade');
				        	  
				        	  parentobj.append('<div class="tab-pane fade in active"  id="'+key+'">'+html+'</div>');
				        	  
				        	  $('.nav a[href="#'+key+'"]').tab('show');
				        	  //console.log(.attr('id'));
  
				        }
				    });
			});
		
			
		}
	}
});