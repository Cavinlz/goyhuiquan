/**
 * 
 */
define(['bst'],function(){
	
	var $lv_table = $('#lv_table');
	
	return {
		run:function(){
			
			$('button[id^=edit-]').each(function(){
				$(this).attr('title','更改信息');
				
				var $prop = $(this).attr('id').split('-');
				
				$(this).on('click',function(){
					var url = $.return_url({ctrl:$prop[1],action:$prop[0],key:$prop[2]});
					window.location = url;
				});
			
			});
			
			$('button[id^=view-]').each(function(){
				$(this).attr('title','查看信息');
				
				var $prop = $(this).attr('id').split('-');
				
				$(this).on('click',function(){
					var url = czcool.host + $prop[1] + '?k='+$prop[2];
					window.location = url;
				});
			
			});
			
			
			$('button[id^=del-]').each(function(){
				$(this).attr('title','删除记录');
				
				var id = $(this).data('id');
				var url = $(this).data('target');
				
				$(this).on('click',function(){
					if(confirm('您确定要删除该记录吗?'))
					{
						console.log(id);
						$.send_ajax({
							url:url,
							params:{card:id},
							sucCal:function(data){
								if(data.code == 200)
									$('#del-'+id).parents('tr:eq(0)').fadeOut();
								else
									$lv_table.prompt({code:parseInt(data.code),msg:data.msg});
							}
						});
					}
				});
			
			});
			
			
			$('div[id^=mySwitch]').on('switch-change', function (e, data) {
			    var $el = $(data.el)  //return the element
			      , value = data.value;
			    //console.log($el.data('id'));
			    
			    $.send_ajax({
			    	url			:		$.return_url({ctrl:'wechatcards',action:'updstatus'})	   ,
			    	params		:		{
			    								k				:		$el.data('id')							, 
			    								status		:		data.value
			    							}																		
			    });
			    
			});
			
		}
		
	}
	
});