define(['editor','bs-wysihtml5'],
function() {'use strict'
	
	var my = {
		
			ctrl	:'api',
			route: null,
			ajax	:function(options){
				
						var def = {
								ctrl:this.ctrl,
								data:''	,
								area:'#mainarea',
								success: function(){}
							};
						var e = $.extend(def,options);
						
						var act = e.action;
						
						$.ajax({
					        url: $.return_url({ctrl:e.ctrl}),
					        type: 'post',
					        data: e.data,
					        dataType: 'json',
					        success: e.success,
					        error: function(xhr, ajaxOptions, thrownError) {
					            alert(xhr.responseText);
					        }
					    });
				
			},
			alw_adt: false,
	        alw_ass: false,
	        seccode: null
			
	};
	
	function redriect(){
		go(my.route);
	}
	
	
	return {
		run:function(){
			
			my.alw_adt = gb.alw_adt || my.alw_adt;
			my.alw_ass = gb.alw_asgn || my.alw_ass;
			my.seccode = gb.seccode;
			
			if (my.alw_ass) {
				my.ajax({
					data:{seccode:my.seccode,cmd:'_gtrnslts'},
					success:function(res){
						 	var html = '<option>auto</option>';
						 	if(res.data != null)
		                    $.each(res.data, function(i, v) {
		                        html += '<option value="' + v.key + '">' + v.val + '</option>';
		                    });
		                    $('#assigneeSelect').html(html);
					}
				});
			}
			else
			{
				$('#obj-translator').remove();
			}
			
			if (my.alw_adt) {
				my.ajax({
					data:{seccode:my.seccode,cmd:'_gadts'},
					success:function(res){
						 	var html = '<option>Myself</option>';
						 	if(res.data != null)
		                    $.each(res.data, function(i, v) {
		                        html += '<option value="' + v.key + '">' + v.val + '</option>';
		                    });
		                    $('#auditSelect').html(html);
					}
				});
			}
			else
			{
				$('#obj-audit').remove();
			}
			
			$("#wysiwyg2").wysihtml5();
			
			//submit task
			$(document).delegate('button[id^=submit]', 'click', function() {
				
				if(!confirm('You Are About To Submit The Essay ?')) return ;
				
				var essayContent = $('#wysiwyg2').val();
				
				if ($.trim(essayContent).length <= 0) {
                    alert('Content can not be empty!');
                    return false;
                }
				
				var params = {
						seccode	:my.seccode,
						cmd		:'pub',
	                    essay_title: $('#essayTitle').val(),
	                    context: essayContent,
	                    priority: $('#prioritySelect').val(),
	                    fid:$('#essayID').val()
	             };
				
				if (my.alw_ass) params.assignee =  $('#assigneeSelect').val();
                if (my.alw_adt) params.auditor = $('#auditSelect').val();
				
				my.ajax({
					data:params,
					success:function(json) {
						$('#mainarea').prompt({code:json.code,msg:json.msg});
	                }
				});

			});
		}
			
	
	
	}
});