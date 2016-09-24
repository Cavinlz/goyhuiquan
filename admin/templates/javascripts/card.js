define(['finput','dpk','bst'],function(){ 'use strict'
	
	var $controller = 'wechatcards';
	
	return {
		run:function(){
			
			var options = {
					//browseClass: "btn btn-primary btn-block",
					showCaption: false,
					showRemove: false,
					showUpload: false,
					overwriteInitial: true,
					allowedFileExtensions: ["jpg", "gif", "png"],
					browseClass: "btn btn-success btn-block",
					browseLabel: "Pick Image",
					browseIcon: '<i class="icon icon-picture"></i> ',
			};
			
			
			if($(czcool.js).data('upd')== 'Y'){
				options.initialPreview = '<img src="'+$(czcool.js).data('img')+'" height="160">';
				options.browseLabel = 'Re-choose Image';
			}
			
			// with plugin options
			$("#prodimg").fileinput(options);

			
			/* Date picker */

			$('#datetimepicker1').datetimepicker({
			      pickTime: true
			});
			$('#datetimepicker2').datetimepicker({
			      pickTime: true
			});
		
			$('div[id^=mySwitch]').on('switch-change', function (e, data) {
			    var $el = $(data.el)  //return the element
			      , value = data.value;
			    //console.log($el.data('id'));
			    
			    $.synAjax({
			    	url			:		$.return_url({ctrl:'wechatcards',action:'updstatus'})	   ,
			    	params		:		{
			    								k				:		$el.data('id')							, 
			    								status		:		data.value
			    							}																		
			    });
			    
			});
			

			$('#color').change(function(){
				
				
				var color = $(this);
				
				var text = color.find("option:selected").text();
				
				var bgcolor = text.split('_')[1];
				//console.log(bgcolor);
				$('#cardsample').add('#usebtn').css({'background-color':bgcolor});
				$('#usebtn').css({'color':'#fff'});
				 
			});
			
			$('input[type=text]').blur(function(){
				
				var id = $(this).attr('id');
				var h5id = $('#h5_'+id).attr('id');
				console.log(h5id);
				if( h5id != undefined){
					$('#'+h5id).html($(this).val());
				}
			});
		}
	}
});