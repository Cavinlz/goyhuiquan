/**
 * 
 */
define(function(){

	$.get_geo({
		succ:function(position){

               //经度
               var longitude =position.coords.longitude;
               //纬度
               var latitude = position.coords.latitude;
               //alert(longitude+' '+latitude);
               
               
        }
	});
	
			
});

