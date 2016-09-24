define(
function() {'use strict'
	
	var my = {
		
			ctrl	:'invoices',
			action	:{
				
				'CKI'	:'checkout'		,
				'CLI'	:'closed'		,
				
			},
			route: null,
			ajax	:function(options){
				
						var def = {
								ctrl:this.ctrl,
								action:'',
								data:''	,
								area:'#mainarea',
								shw_wait: true,
								shw_done: true
							};
						var e = $.extend(def,options);
						
						var act = e.action;
						
						$.ajax({
					        url: $.return_url({ctrl:e.ctrl,action:this.action[act]}),
					        type: 'post',
					        data: e.data,
					        dataType: 'json',
					        beforeSend: function() {
					        	if(e.shw_wait)
					        		$.wait();
							},
					        complete: function() {
					        	if(e.shw_done)
					        		$.done();
					        },
					        success: function(json) {
					        		
					        	if(json['code'] == 200){
				        			//if(e.mailing) $.mail(e.data.key);
				        			if(json['go']){
				        				go(json['go']);
				        			}
				        			else
				        				$(e.area).prompt({code:json['code'],msg:json['msg']});
				        		} 
				        		else if (json['go']){
					            	go(json['go']);
					            } 
				        		else if (json['msg']){
					            	 $(e.area).prompt({code:json['code'],msg:json['msg']});
					            }
					        },
					        error: function(xhr, ajaxOptions, thrownError) {
					            alert(xhr.responseText);
					        }
					    });
				
			}
			
	};
	
	
	return {
		run:function(){
			$(document).delegate('a[id^=CKI-]', 'click', function() {
				if(confirm('You Are About To Checkout This Order ?')){
					var arr = $(this).attr('id').split('-');
					my.ajax({
						action:arr[0],
						data:{key:arr[1]}
					});
				}
			});
			
			$(document).delegate('a[id^=CLI-]', 'click', function() {
				if(confirm('You Are About To Close This Order ?')){
					var arr = $(this).attr('id').split('-');
					my.ajax({
						action:arr[0],
						data:{key:arr[1]}
					});
				}
			});
		}
			
	
	
	}
});