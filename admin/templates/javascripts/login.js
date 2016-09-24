define(['md5'],
function(md5) {'use strict'
	
	return {
		run:function(){
			var redirect = $('#redirect').val();
			console.log(redirect);
			$(document).delegate('#signin', 'click', function() {
				var mybtn = $('#'+$(this).attr('id'));
				var myform = '#loginform';
				
				
				var credential = md5($(myform+' input[type=\'password\']').val());
							
				$(myform+' input[type=\'hidden\']').val(credential);
				
			    $.ajax({
			        url: $.return_url({ctrl:'login',action:'signin'}),
			        type: 'post',
			        data: $(myform+' input[type=\'text\'], '+myform+' input[type=\'hidden\']'),
			        dataType: 'json',
			        beforeSend: function() {
			        	mybtn.button('loading');
					},
			        complete: function() {
			        	mybtn.button('reset');
			        },
			        success: function(json) {
			            $('.alert').remove();
			            var redirect = $('#redirect').val();
			            if (json['code'] != czcool.rc.success) {
			                if (json['msg']) {
			                    $(myform).prompt({code:json['code'],msg:json['msg']});
			                }
			            }else if(redirect != ''){
			            	
			            	go(redirect);
			            } 
			            else if (json['go']){
			            	go(json['go']);
			            	//console.log(json['go']);
			            } else{
			            	 $(myform).prompt({code:json['code'],msg:json['msg']});
			            }
			        },
			        error: function(xhr, ajaxOptions, thrownError) {
			            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			        }
			    });
			});
		}
	}
});