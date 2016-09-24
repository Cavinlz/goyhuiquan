define(['editor','bs-wysihtml5'],
function() {'use strict'
	
	var my = {
		
			ctrl	:'articles',
			action	:{
				
				'TT'	:'takeit'		,
				'AT'	:'assign'		,
				'ST'	:'savetask'		,
				'SUBT'	:'subtask'		,
				'PA'	:'passaudit'	,
				'RA'	:'reject'		,
				'ACET'	:'takeit'		,
				'REJT'	:'rejtask'
			},
			route: null,
			ajax	:function(options){
				
						var def = {
								ctrl:this.ctrl,
								action:'',
								data:''	,
								area:'#mainarea',
								shw_wait: true,
								shw_done: true,
								mailing:false
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
				        			if(e.mailing) $.mail(e.data.key);
				        			if(json['go']){
				        				my.route = json['go'];
				        				setTimeout(redriect,1000);
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
				
			},
			uk:null
			
	};
	
	function redriect(){
		go(my.route);
	}
	
	
	return {
		run:function(){
			if($('div[id^=actlog-]').attr('id') != undefined)
			{
				my.uk = $('div[id^=actlog-]').attr('id').split('-')[1];
				
				$.ajax({
	                url: $.return_url({ctrl:'asyn',action:'getactlog'}),
	                data:{k:my.uk},
	                method:'post',
	                dataType: 'html',
	                success: function(html) {
	                	
	                	$('#actlog-'+my.uk).html(html);
	                   
	                },
	                error: function(xhr, ajaxOptions, thrownError) {
	                	$('#actlog-'+my.uk).html(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	                }
	            });
			}
			
			
			$(document).delegate('a[id^=TT]', 'click', function() {
				if(confirm('Are You Sure You Want To Take Up The Translation Task ? ')){
					var arr = $(this).attr('id').split('-');
					my.ajax({
						action:arr[0],
						data:{key:arr[1]}
					});
				}
			});
			
			
			$(document).delegate('a[id^=AT]', 'click', function() {

					var key =  $(this).attr('id').split('-')[1];
					
					$.ajax({
				        url: $.return_url({ctrl:'fetchapi',action:'getemployees'}),
				        type: 'post',
				        data: {key:key},
				        dataType: 'json',
				        beforeSend: function() {
				        	$.wait();
						},
				        complete: function() {
				        	
				        },
				        success: function(json) {
				        	var _h = '';
				        	for(var i in json.data){
		                        _h += '<option value="'+json.data[i].key+'">'+json.data[i].val+'</option>';
		                    }
		                    $('#assignModal').modal();
		                    $('#assignSelect').html(_h);
		                    $('#assignSm').off().click(function(){
		                
		                    	$.ajax({
		    				        url: $.return_url({ctrl:'articles',action:'assign'}),
		    				        type: 'post',
		    				        data: {key:key,assignee:$('#assignSelect').val()},
		    				        dataType: 'json',
		    				        success:function(json){
		    				        	 if (json['go']){
		    					            	go(json['go']);
		    					           } 
		    				        },
		    				        complete:function(){
		    				        	$.done();
		    				        }
		    				        });
		                    	
		                    	
		                    });
				        },
				        error: function(xhr, ajaxOptions, thrownError) {
				            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				        }
				    });

			});
			
			$("#wysiwyg2").wysihtml5();
			
			$(document).delegate('a[id^=ST-]', 'click', function() {
				var arr = $(this).attr('id').split('-');
				my.ajax({
					action:arr[0],
					data:{key:arr[1],context:$('#wysiwyg2').val(),essay_title:$('#title').val()}
				});
			});
			
			//submit task
			$(document).delegate('a[id^=SUBT]', 'click', function() {
				
				if(!confirm('You Are About To Submit The Translation Task ?')) return ;
				var arr = $(this).attr('id').split('-');
				
				my.ajax({
					action:arr[0],
					data:{key:arr[1],context:$('#wysiwyg2').val(),essay_title:$('#title').val()},
					mailing:true
				});

			});
			//accept translation
			$(document).delegate('a[id^=PA]', 'click', function() {
				
				if(!confirm('You Are About To Accpet The Translation From Translator ?')) return ;
				
				var arr = $(this).attr('id').split('-');
				my.ajax({
					action:arr[0],
					data:{key:arr[1]},
					mailing:true
				});

			});
			//reject translation
			$(document).delegate('a[id^=RA]', 'click', function() {
				
				if(!confirm('You Are About To Reject The Translation From Translator ?')) return ;
				
				var arr = $(this).attr('id').split('-');
				my.ajax({
					action:arr[0],
					data:{key:arr[1]},
					mailing:true
				});

			});
			
			//accept assigned task
			$(document).delegate('a[id^=ACET]', 'click', function() {
				
				if(!confirm('You Are About To Accept The Assigned Translation Task?')) return ;
				
				var arr = $(this).attr('id').split('-');
				my.ajax({
					action:arr[0],
					data:{key:arr[1]},
					mailing:true
				});

			});
			//reject assigned task
			$(document).delegate('a[id^=REJT]', 'click', function() {
				
				if(!confirm('You Are About To Refuse The Assigned Translation Task?')) return ;
				
				var arr = $(this).attr('id').split('-');
				my.ajax({
					action:arr[0],
					data:{key:arr[1]},
					mailing:true
				});

			});
		}
			
	
	
	}
});