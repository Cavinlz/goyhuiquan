define(['finput','dpk'],function(){ 'use strict'
	
	var $controller = 'product';
	
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
			      pickTime: false
			});
			$('#datetimepicker2').datetimepicker({
			      pickTime: false
			});
		
			
		}
	}
});