define(['finput','dpk'],function(){ 'use strict'
	
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
					browseLabel: "点击上传图片",
					browseIcon: '<i class="icon icon-picture"></i> ',
			};
			
			
			if($(czcool.js).data('upd')== 'Y'){
				options.initialPreview = '<img src="'+$(czcool.js).data('img')+'" height="160">';
				options.browseLabel = '重新选择';
			}
			
			$('#datetimepicker1').datetimepicker({
			      pickTime: false
			});
			// with plugin options
			$("#prodimg").fileinput(options);

		}
	}
});