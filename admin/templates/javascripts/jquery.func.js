(function($){
	$.fn.extend({
		prompt:function(options){
        	var def = {
					code:0, /* type of the alert */
					msg:'',
					type:'',
					obj:''
				};
        	var alert = $.extend(def,options);
        	
        	var icon = '';
        	if(alert.code > 0)
        	{
        		switch (alert.code){
        			case 200:
        				alert.type='alert-success';
        				icon = "<i class='icon icon-ok-sign'></i> Success: ";
        				break;
        			case 201:
        				alert.type = 'alert-danger';
        				icon = "<i class='icon icon-minus-sign'></i> Error: ";
        				break;
        			case 202:
        				alert.type = 'alert-warning';
        				icon = "<i class='icon-warning-sign'></i> Warning: ";
        				break;
        			default:
        				alert.type = 'alert-info';
        		}
        	}
        	$('.alert').remove();
        	var obj = '<div class="alert '+alert.type+' alert-dismissable">'+icon+alert.msg +
        					'<button type="button" class="close" data-dismiss="alert">&times;</button>'+
        						
        			 '</div>';
        	$(obj).insertBefore(this);
        },
        czmodal:function(options){
        	var def = {
					title:'',
					id:'#czmodal',
					type:'',
					obj:''
				};
        	var alert = $.extend(def,options);
        }
        
	});
	
	$.extend({
		
		//get json request url
		json_url:function(options){
			var def = {
							model:'',
							action:''
			};
			var my = $.extend(def,options);
			
			return my.model+'/'+my.action+'/request';
		},
		return_url:function(options){
			var def = {
					ctrl:'',
					action:'index',
					key:''
				};
				var my = $.extend(def,options);
				
				var k='';
				if(config.url_friendly)
				{
					var dir = (config.language == 'en' || config.language == '')? '' : config.language+'/';
					var html = (config.url_style.hashtml)?'.html':'';
					//url = config.domain + dir + my.ctrl	+ '-' + my.action + '.html';
					url = config.domain + dir + my.ctrl	+ '/' + my.action + html;
				}
				else
				{
					if(my.ctrl != ''){
						k += 'ctrl='+my.ctrl;
					}
					
					if(my.action!=''){
						k += '&act='+my.action;
					}
					
					if(my.key!=''){
						k += '&k='+my.key;
					}
					
					if(config.language != 'en' || config.language != ''){
						k += '&lg='+my.key;
					}
					
					url = config.domain + k;
					
				}	
				
				return url;
		},
		
        logg:function(msg){
        	try{
				if(gconfig.debug == true)
					console.log(msg);
			}catch(e){
				
			}
        }
		,
		wait:function(e){
        	if(e=='done'){
        		
        	}else{
        		
        	}
        },
        done:function(){
        	
        }
        ,
        mail:function(k){
        	
        	$.ajax({
		        url: $.return_url({ctrl:'sendmail',action:'index'}),
		        type: 'post',
		        data: {key:k},
		        //async:false,
		        success:function(){
		        	
		        },
		        complete:function(){
		        	$.done();
		        }
		    });
        }
	});
})(jQuery);


function go(url)
{
	window.location = url;
}

function alertmsg(obj,msg,code)
{
	$('#'+obj).prompt({code:parseInt(code),msg:msg});
}