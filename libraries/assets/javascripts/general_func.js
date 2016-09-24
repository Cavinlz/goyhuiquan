(function($){
	fms = $.fn;
	fms.extend({
		go_center:function(){
			var top = ($(window).height() - this.height())/2;
			var left = ($(window).width() - this.width())/2;
			var scrollTop = $(document).scrollTop();
			var scrollLeft = $(document).scrollLeft();
			return this.css({position:'absolute',"top":top+scrollTop,left:left+scrollLeft});
		},
		modal:function(options){
				
		},
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
        }
        
	});
	
	$.extend({
		/* ****************************************************
		 * Function: dynamic including the reg validation js file
		 * Author:Cavin  @  18 May 2013
		 */
		load_js:function(filename){
			 var oHead = document.getElementsByTagName('HEAD').item(0);
			    var oScript= document.createElement("script");
			    oScript.type = "text/javascript";
			    oScript.src=$CONFIG.HOST+"/js/jquery."+filename+".js?v=12345";
			    oHead.appendChild( oScript);
		},
		log:function(message){
			try{
				console.log(message);
			}catch(e){
				
			}
		},
		return_url:function(options){
			var def = {
					ctrl:'',
					action:'',
					key:''
				};
				var my = $.extend(def,options);
				
				var k='';
				if(czcool.url_friendly)
				{
					//var dir = (czcool.language == 'en' || czcool.language == '')? '' : czcool.language+'/';
					
					url = czcool.host + czcool.admin+ my.ctrl	+ '/' + my.action;
					//console.log(czcool.host);
					if(my.key != '')
						url += '?k='+my.key;
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
					
					
					url = czcool.host + czcool.admin + k;
					
				}	
				
				return url;
		},
		send_ajax:function(options){
			var def = {
					url:'', 
					params:{},
					sucCal:function(){},
					errCal:function(){},
					cmpCal:function(){},
					type:'post'
				};
			var me = $.extend(def,options);
			
			$.ajax({
                type: me.type,
                url: me.url,
                data: me.params,
                dataType: 'json',
                success: me.sucCal,
                error: me.errCal,
                complete:me.cmpCal
            });
		},
		jmdialog:function(options){
        	
			var def = {
					trigger:'#lnkDialog', 
					txtobj:'#dialog-text',
					diaheader:"#dialog-header",
					html:'',
					code:201
				};
			var me = $.extend(def,options);
			
			
			switch(me.code){
				case '201':
					$(me.diaheader).html('出错啦!');
					break;
				case '200':
					$(me.diaheader).html('恭喜啦!');
					break;
			}
			
			$(me.txtobj).html(me.html);
    		$(me.trigger).click();
        },
        get_geo:function(options){
        	
        	var def = {
        			accuracy:true,
                    maxage:1000,
                    succ:function(){},
                    err:function(){}
				};
			var me = $.extend(def,options);
			
			var option = {
        			enableHighAccuracy:me.accuracy,
                    maximumAge:me.maxage
				};
			
			if(navigator.geolocation){
                //browser supports geolocation
                navigator.geolocation.getCurrentPosition(me.succ,me.err,option);
            }else{
                return false;
            }
			
        	
        },
        get_location:function(options){
        	
        	var def = {
					api:'',
					lg:'',
					la:'',
					succ:function(){},
					err:function(){}
				};
			var me = $.extend(def,options);
			
			$.send_ajax({
				url:me.api,
				params:{la:me.la,lg:me.lg},
				sucCal:me.succ
			});
        },
        get_loc_vip:function(options){
        	var def = {
					api:'http://api.map.baidu.com/location/ip?ak=MVRLzbYKOOGfmwobnWZGXgGS',
				};
        	var me = $.extend(def,options);
        	$.send_ajax({
				url:me.api,
				sucCal:function(data){
					console.log(data);
					//if(data.addressComponent) alert(data.addressComponent.city);
						
				}
			});
        },
        is_android:function(){
        	var u = navigator.userAgent;
        	if(u.indexOf('Android') > -1 || u.indexOf('Linux') > -1){
        		return true;
        	}
        	return false;
        }
	});
})(jQuery);


function alertmsg(obj,msg,code)
{
	$('#'+obj).prompt({code:parseInt(code),msg:msg});
}
function go(url, parent)
{
	if(parent != undefined) window.parent.location = url;
	
	else window.location = url;
}
function progressUpd(obj, msg, code)
{
	var message ;
	code = parseInt(code);
	switch(code)
	{
		case 200: //success
			message = '<p><span class="text-green">'+msg+'</span></p>';
		break;
		case 201: //error
			message = '<p><span class="text-contrast">'+msg+'</span></p>';
		break;
		case 202: //warning
			message = '<p><span class="text-orange">'+msg+'</span></p>';
		break;
		default:
			message = '<p>'+msg+'</p>';
	}
	//console.log(message);
	$('#'+obj).append(message);
}

