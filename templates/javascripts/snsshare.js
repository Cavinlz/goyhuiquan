(function($){
	fms = $.fn;
	fms.extend({
		
        
	});
	
	$.extend({
		wx_share:function(value){
			//alert(location.href.split('#')[0]);
			
			$.ajax({
				url:'http://www.yizhita.com/wcauth/authtoken',
				data:{url:encodeURIComponent(location.href.split('#')[0])},
				type:'post',
				dataType:'json',
				complete:function(){
					
				},
				error:function(XMLHttpRequest, textStatus, errorThrown){
					alert(XMLHttpRequest.status+'||'+XMLHttpRequest.readyState+'||'+textStatus);

				},
				success:function(data){
					//alert(data);
					var c = data.body;
					wx.config({
					    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
					    appId: c.appId, // 必填，公众号的唯一标识
					    timestamp: c.timestamp, // 必填，生成签名的时间戳
					    nonceStr: c.nonceStr, // 必填，生成签名的随机串
					    signature: c.signature,// 必填，签名，见附录1
					    jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareWeibo'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
					});
					
					wx.error(function(res){
						alert("config信息验证失败"+res);
					    // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
					});
					wx.ready(function(){
						//alert('ok');
						
						//分享到朋友圈
						wx.onMenuShareTimeline({
						    title: value.title + '-' +value.desc, // 分享标题
						    link: value.link, // 分享链接
						    imgUrl: value.imgUrl, // 分享图标
						    success: function () { 
						        // 用户确认分享后执行的回调函数
						    	value.success();
						    },
						    cancel: function () { 
						        // 用户取消分享后执行的回调函数
						    	value.cancel();
						    }
						});
						//分享给朋友
						wx.onMenuShareAppMessage({
						    title: value.title, // 分享标题
						    desc: value.desc, // 分享描述
						    link: value.link, // 分享链接
						    imgUrl: value.imgUrl, // 分享图标
						    type: value.type, // 分享类型,music、video或link，不填默认为link
						    dataUrl: value.dataUrl, // 如果type是music或video，则要提供数据链接，默认为空
						    success: function () { 
						        // 用户确认分享后执行的回调函数
						    	 value.success();
						    },
						    cancel: function () { 
						        // 用户取消分享后执行的回调函数
						    	value.cancel();
						    }
						});
						
						wx.onMenuShareWeibo({
						    title: value.title, // 分享标题
						    desc: value.desc, // 分享描述
						    link: value.link, // 分享链接
						    imgUrl: value.imgUrl, // 分享图标
						    success: function () { 
						       // 用户确认分享后执行的回调函数
						    },
						    cancel: function () { 
						        // 用户取消分享后执行的回调函数
						    }
						});
						
						wx.onMenuShareQQ({
						    title: value.title, // 分享标题
						    desc: value.desc, // 分享描述
						    link: value.link, // 分享链接
						    imgUrl: value.imgUrl, // 分享图标
						    success: function () { 
						       // 用户确认分享后执行的回调函数
						    },
						    cancel: function () { 
						       // 用户取消分享后执行的回调函数
						    }
						});
						
					});
				  }//success
				});
		},
		wc_card:function(){
			
			var $url = $.return_url({ctrl:'wcauth',action:'authtoken'});
			
			//console.log(url);
			
			$.ajax({
				url:$url,
				data:{url:encodeURIComponent(location.href.split('#')[0])},
				type:'post',
				dataType:'json',
				complete:function(){
					
				},
				error:function(XMLHttpRequest, textStatus, errorThrown){
					alert(XMLHttpRequest.status+'||'+XMLHttpRequest.readyState+'||'+textStatus);

				},
				success:function(data){
					//alert(data);
					var c = data.body;
					wx.config({
					    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
					    appId: c.appId, // 必填，公众号的唯一标识
					    timestamp: c.timestamp, // 必填，生成签名的时间戳
					    nonceStr: c.nonceStr, // 必填，生成签名的随机串
					    signature: c.signature,// 必填，签名，见附录1
					    jsApiList: ['addCard','chooseCard','openCard'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
					});
				}
			});
		},
		wc_add_card:function(options){
			
			var def = {
					
					timestamp:'',
					card_id:'',
					nonce_str:''
			};
			
			var card = $.extend(def, options);
			
			var $url = $.return_url({ctrl:'wcauth',action:'authcardtoken'});
			
			$.ajax({
				url:$url,
				data:{card_id:card.card_id},
				type:'post',
				dataType:'json',
				complete:function(){
					
				},
				error:function(XMLHttpRequest, textStatus, errorThrown){
					alert(XMLHttpRequest.status+'||'+XMLHttpRequest.readyState+'||'+textStatus);

				},
				success:function(data){
					//alert(data);
					var c = data.body;
					wx.addCard({

					    cardList: [{

					        cardId: card.card_id,
					        //cardExt: "{'timestamp':"+c.timestamp+",'nonce_str':'"+c.nonceStr+"','signature':'"+c.signature+"','code':'','openid':''}"
					        cardExt: '{"code":"","openid":"","timestamp":"'+c.timestamp+'","signature":"'+c.signature+'"}'			
					    }], // 需要添加的卡券列表

					    success: function (res) {

					        var cardList = res.cardList; // 添加的卡券列表信息

					    }

					});
				}
			});
			
		},
		wc_add_mu_card:function(options){
			
			var def = {
					
					timestamp:'',
					card_id:'',
					nonce_str:''
			};
			
			var card = $.extend(def, options);
			
			var $url = $.return_url({ctrl:'wcauth',action:'authcardstoken'});
			console.log(card.card_id);
			$.ajax({
				url:$url,
				data:{card_id:card.card_id},
				type:'post',
				dataType:'json',
				complete:function(){
					
				},
				error:function(XMLHttpRequest, textStatus, errorThrown){
					alert(XMLHttpRequest.status+'||'+XMLHttpRequest.readyState+'||'+textStatus);

				},
				success:function(data){
					//alert(data);
					
					if(data.msg!=undefined){
						alert(data.msg);
						return;
					}
					
					var c = data.body;
					var cards = data.code;
					var cardlist = new Array();
					
					for(var i in cards){
						console.log(cards[i]);
						cardlist.push({cardId:cards[i],cardExt:'{"code":"","openid":"","timestamp":"'+c[i].timestamp+'","signature":"'+c[i].signature+'"}'}); 
					}
					console.log(cardlist);
					wx.addCard({

					    cardList: cardlist, // 需要添加的卡券列表

					    success: function (res) {

					        var cardList = res.cardList; // 添加的卡券列表信息

					    }

					});
				}
			});
			
		}
		
	});
})(jQuery);

