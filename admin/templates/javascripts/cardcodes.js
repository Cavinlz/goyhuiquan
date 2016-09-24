define(['finput'],function(){ 'use strict'
	
	var $controller = 'product';
	
	return {
		run:function(){
			
			var options = {
					//browseClass: "btn btn-primary btn-block",
					showCaption: false,
					showRemove: false,
					showUpload: false,
					overwriteInitial: true,
					allowedFileExtensions: ["txt"],
					browseClass: "btn btn-success btn-block",
					browseLabel: "选择 .txt 文件",
					browseIcon: '<i class="icon icon-file"></i> ',
			};
			
			
			// with plugin options
			$("#prodimg").fileinput(options);

			$('#manualbtn').click(function(){
				$('#progresslog').html('')
				$('#manualupload').submit();
			});
			
			$('#filebtn').click(function(){
				$('#progresslog').html('')
				$('#fileupload').submit();
			});
			
			$('#Mymodal').on('shown.bs.modal',function(e){
				
			});
			
			$('#provider').change(function(){
				var val = $(this).find('option:selected').val();
				//console.log(val);
//				$('input[class=code_provider]:hidden').val(val);
				$('#code_provider_1').add('#code_provider_2').val(val);
			});
		}
	}
});