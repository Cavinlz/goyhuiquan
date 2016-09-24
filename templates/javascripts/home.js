define(['wcjdk'],function(){
	
	if(getgeo){
		$.get_geo({
			succ:function(position){

	               //经度
	               var longitude =position.coords.longitude;
	               //纬度
	               var latitude = position.coords.latitude;
	               //alert(longitude+' '+latitude);
	               var $url = $.return_url({ctrl:'api',action:'getgeo'});
	               $.ajax({
	       			url:$url,
	       			data:{lt:latitude,lg:longitude},
	       			type:'post',
	       			dataType:'json',
	       			success:function(data){
	       				if(data.code != 200){
	       					//alert(data.msg);
	       				}
	       			}
	       		});
	        }
		});
	}
	
	$.wc_card();
	
	$(document).delegate('button[id^=add-card-]','click',function() {
		var card = $(this).data('card');
		//console.log(card);
		$.wc_add_card({card_id:card});
	});
	
	//GetRTime();
	var len = timeArray.length;
	//console.log(timeArray);

	$.each(timeArray,function(n,value) { 
		//console.log(n+' :'+ value);
		GetRTime(value,n);
	})
	
	$('#getall').click(function(){
		
		var phone = $('#cellphone').val();
		
		if(!isphone(phone)){
			alert('手机号码不正确,请重新输入.');
			return;
		}
		var $url = $.return_url({ctrl:'member',action:'addphone'});
		$.ajax({
			url:$url,
			data:{phone:phone},
			type:'post',
			dataType:'json',
			complete:function(){
				
			},
			success:function(data){
				if(data.code != 200){
					alert(data.msg);return;
				}
				
				$.wc_add_mu_card({card_id:cardArray});
			}
		});
			
	});
	
	
	$(document).on("click", 'button[id^=like-]' ,function(event){
		var $this = $(this).attr('id');
		var $key = $this.split('-')[1];
		var $card = $this.split('-')[2];
		$.send_ajax({
			url:$.return_url({ctrl:'brands',action:'updlikes'}),
			params:{k:$key,type:'L'},
			sucCal:function(data){
				if(data.code == 200){
					
					
					
					$('label[id^=like-stat-'+$key+']').each(function(){
						$(this).html(parseInt($(this).html())+1);
						
					});
					
					$('button[id^=like-'+$key+']').each(function(){
						var id = $(this).attr('id').split('-');
						$(this).html('<i class="icon-heart"></i>');
						$(this).attr('id','unlike-'+$key+'-'+id[2]);
					});
					
					//$('#like-stat-'+$key).html(parseInt($('#like-stat-'+$key).html())+1);
					//$('#'+$this).attr('id','unlike-'+$key+'-'+$card);
					//$('#'+$this).attr('class','likes-tag');
				}
			}
		});
		
		
		
	});
	
	
	$(document).on("click", 'button[id^=unlike-]' ,function(event){
		var $this = $(this).attr('id');
		var $key = $this.split('-')[1];
		var $card = $this.split('-')[2];
		//if(confirm('您确实要取消信赖该品牌吗?'))
		$.send_ajax({
			url:$.return_url({ctrl:'brands',action:'updlikes'}),
			params:{k:$key,type:'U'},
			sucCal:function(data){
				if(data.code == 200){
					
					
					$('label[id^=like-stat-'+$key+']').each(function(){
						$(this).html(parseInt($(this).html())-1);
						
					});
					
					$('button[id^=unlike-'+$key+']').each(function(){
						var id = $(this).attr('id').split('-');
						$(this).html('<i class="icon-heart-empty"></i>');
						$(this).attr('id','like-'+$key+'-'+id[2]);
					});
					
					//$('#like-stat-'+$key).html(parseInt($('#like-stat-'+$key).html())+1);
					//$('#'+$this).attr('id','unlike-'+$key+'-'+$card);
					//$('#'+$this).attr('class','likes-tag');
				}
			}
		});
		
		
		
	});
	
});

function GetRTime(EndTime, counter) {      
	var timestamp = Date.parse(new Date());
	timestamp = timestamp/1000;
    if(timestamp < EndTime)  
    {                      
        var nMS = EndTime - timestamp;   
        var nD = (Math.floor(nMS / (60 * 60 * 24))).toString();  
        var nH = (Math.floor(nMS / (60 * 60)) % 24).toString();  
        var nM = (Math.floor(nMS / (60)) % 60).toString();  
        var nS = (Math.floor(nMS) % 60).toString();  
        //document.getElementById("RemainD").innerHTML = nD;  
        nH = nD * 24 + parseInt(nH);
        //$("#RemainD-"+counter).html(nD);
        $("#RemainH-"+counter).html((nH.length < 2)?'0'+nH : nH);  
        $("#RemainM-"+counter).html((nM.length < 2)?'0'+nM : nM);  
        $("#RemainS-"+counter).html((nS.length < 2)?'0'+nS : nS);   
        setTimeout("GetRTime("+EndTime+","+counter+")", 1000);                     
    }  
   
}

function isphone(inputString)
{
     var partten = /^1[3,5,8]\d{9}$/;
     var fl=false;
     if(partten.test(inputString))
     {
          return true;
     }
     else
     {
          return false;
     }
}