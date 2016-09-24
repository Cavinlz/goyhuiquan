<?php 
//get the template url
$template_url = CFactory::getApplicationTemplateURL();
$openid = User::get_user_session_Openid();
?>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>大转盘活动</title>
    <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo $template_url?>/../../html/css/style.css" rel="stylesheet" type="text/css">
    <link href='<?php echo $template_url;?>/styles/yhuigo.css' media='all' rel='stylesheet' type='text/css' />
    <script type="text/javascript" src="<?php echo $template_url?>/../../html/js/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="<?php echo $template_url?>/../../html/js/awardRotate.js"></script>
    <script type="text/javascript">
        var turnplate = {
            restaraunts: [],				//大转盘奖品名称
            colors: [],					//大转盘奖品区块对应背景颜色
            outsideRadius: 180,			//大转盘外圆的半径
            textRadius: 155,				//大转盘奖品位置距离圆心的距离
            insideRadius:60,			//大转盘内圆的半径
            startAngle: 0,				//开始角度

            bRotate: false				//false:停止;ture:旋转
        };

        var userId = "<?=$openid; ?>";
        var rotateFn;
        $(document).ready(function () {
            //动态添加大转盘的奖品与奖品区域背景颜色
            turnplate.restaraunts = ["惊喜大奖!", "谢谢参与", "优惠提醒奖!","谢谢参与", "惊喜大奖!", "谢谢参与", "优惠提醒奖!","谢谢参与"];
            turnplate.colors = ["#f15366", "#FFD725", "#fff600", "#FFD725", "#f15366", "#FFD725", "#fff600", "#FFD725", "#FFCA00", "#FFD725"]
                    //["#FFF4D6", "#FFFFFF", "#FFF4D6", "#FFFFFF", "#FFF4D6", "#FFFFFF", "#FFF4D6", "#FFFFFF", "#FFF4D6", "#FFFFFF"];


            var rotateTimeOut = function () {
                $('#wheelcanvas').rotate({
                    angle: 0,
                    animateTo: 2160,
                    duration: 8000,
                    callback: function () {
                        alert('网络超时，请检查您的网络设置！');
                    }
                });
            };

            //旋转转盘 item:奖品位置; txt：提示语;
             rotateFn = function (item, txt) {
                console.log(item+" "+txt);
                var angles = item * (360 / turnplate.restaraunts.length) - (360 / (turnplate.restaraunts.length * 2));
                if (angles < 270) {
                    angles = 270 - angles;
                } else {
                    angles = 360 - angles + 270;
                }
                $('#wheelcanvas').stopRotate();
                $('#wheelcanvas').rotate({
                    angle: 0,
                    animateTo: angles + 1800,
                    duration: 8000,
                    callback: function () {
                        if(item == 1){
                        	alert('恭喜您,获得'+txt+'! 请尽快到门店兑换，先兑先得哦 ^^');
                        }
                        else if(item == 3){
                        	alert('恭喜您,摇得'+txt+'! 优惠详情请到门店咨询哦 ^^');
                        }
                        else{
                        	alert('不要气馁哦,明天我们再来! ^^');
                       	}
                        //alert(txt);
                        turnplate.bRotate = !turnplate.bRotate;
                    }
                });
            };

            $('.pointer').click(function () {
                getPrize(userId);
            });
        });

        function rnd(n, m) {
            var random = Math.floor(Math.random() * (m - n + 1) + n);
            return random;

        }


        //页面所有元素加载完毕后执行drawRouletteWheel()方法对转盘进行渲染
        window.onload = function () {
            drawRouletteWheel();
        };

        function drawRouletteWheel() {
            var canvas = document.getElementById("wheelcanvas");
            if (canvas.getContext) {
                //根据奖品个数计算圆周角度
                var arc = Math.PI / (turnplate.restaraunts.length / 2);
                var ctx = canvas.getContext("2d");
                //在给定矩形内清空一个矩形
                ctx.clearRect(0, 0, 422, 422);
                //strokeStyle 属性设置或返回用于笔触的颜色、渐变或模式
                ctx.strokeStyle = "#000000";//"#FFBE04";
                //font 属性设置或返回画布上文本内容的当前字体属性
                ctx.font = '16px Microsoft YaHei';
                for (var i = 0; i < turnplate.restaraunts.length; i++) {
                    var angle = turnplate.startAngle + i * arc;
                    ctx.fillStyle = turnplate.colors[i];
                    ctx.beginPath();
                    //arc(x,y,r,起始角,结束角,绘制方向) 方法创建弧/曲线（用于创建圆或部分圆）
                    ctx.arc(211, 211, turnplate.outsideRadius, angle, angle + arc, false);
                    ctx.arc(211, 211, turnplate.insideRadius, angle + arc, angle, true);
                    ctx.stroke();
                    ctx.fill();
                    //锁画布(为了保存之前的画布状态)
                    ctx.save();

                    //----绘制奖品开始----
                    ctx.fillStyle = "#000000";
                    var text = turnplate.restaraunts[i];
                    var line_height = 17;
                    //translate方法重新映射画布上的 (0,0) 位置
                    ctx.translate(211 + Math.cos(angle + arc / 2) * turnplate.textRadius, 211 + Math.sin(angle + arc / 2) * turnplate.textRadius);

                    //rotate方法旋转当前的绘图
                    ctx.rotate(angle + arc / 2 + Math.PI / 2);

                    /** 下面代码根据奖品类型、奖品名称长度渲染不同效果，如字体、颜色、图片效果。(具体根据实际情况改变) **/
                    if (text.indexOf("M") > 0) {//流量包
                        var texts = text.split("M");
                        for (var j = 0; j < texts.length; j++) {
                            ctx.font = j == 0 ? 'bold 20px Microsoft YaHei' : '16px Microsoft YaHei';
                            if (j == 0) {
                                ctx.fillText(texts[j] + "M", -ctx.measureText(texts[j] + "M").width / 2, j * line_height);
                            } else {
                                ctx.fillText(texts[j], -ctx.measureText(texts[j]).width / 2, j * line_height);
                            }
                        }
                    } else if (text.indexOf("M") == -1 && text.length > 6) {//奖品名称长度超过一定范围
                        text = text.substring(0, 6) + "||" + text.substring(6);
                        var texts = text.split("||");
                        for (var j = 0; j < texts.length; j++) {
                            ctx.fillText(texts[j], -ctx.measureText(texts[j]).width / 2, j * line_height);
                        }
                    } else {
                        //在画布上绘制填色的文本。文本的默认颜色是黑色
                        //measureText()方法返回包含一个对象，该对象包含以像素计的指定字体宽度
                        ctx.fillText(text, -ctx.measureText(text).width / 2, 0);
                    }

                    //添加对应图标
                    if (text.indexOf("闪币") > 0) {
                        var img = document.getElementById("shan-img");
                        img.onload = function () {
                            ctx.drawImage(img, -15, 10);
                        };
                        ctx.drawImage(img, -15, 10);
                    } else if (text.indexOf("谢谢参与") >= 0) {
                        var img = document.getElementById("sorry-img");
                        img.onload = function () {
                            ctx.drawImage(img, -15, 10);
                        };
                        ctx.drawImage(img, -15, 10);
                    }
                    //把当前画布返回（调整）到上一个save()状态之前
                    ctx.restore();
                    //----绘制奖品结束----
                }
            }
        }

        function runPrize(item)
        {
            if (turnplate.bRotate)return;
            turnplate.bRotate = !turnplate.bRotate;
            //获取随机数(奖品个数范围内)
//            var item = rnd(1, turnplate.restaraunts.length);
//            alert(item +"  "+turnplate.restaraunts.length)
            //奖品数量等于10,指针落在对应奖品区域的中心角度[252, 216, 180, 144, 108, 72, 36, 360, 324, 288]
            rotateFn(item, turnplate.restaraunts[item - 1]);
            /* switch (item) {
             case 1:
             rotateFn(252, turnplate.restaraunts[0]);
             break;
             case 2:
             rotateFn(216, turnplate.restaraunts[1]);
             break;
             case 3:
             rotateFn(180, turnplate.restaraunts[2]);
             break;
             case 4:
             rotateFn(144, turnplate.restaraunts[3]);
             break;
             case 5:
             rotateFn(108, turnplate.restaraunts[4]);
             break;
             case 6:
             rotateFn(72, turnplate.restaraunts[5]);
             break;
             case 7:
             rotateFn(36, turnplate.restaraunts[6]);
             break;
             case 8:
             rotateFn(360, turnplate.restaraunts[7]);
             break;
             case 9:
             rotateFn(324, turnplate.restaraunts[8]);
             break;
             case 10:
             rotateFn(288, turnplate.restaraunts[9]);
             break;
             } */
            console.log(item);
        }

        // AJAX方法, 被自定义封装在该函数中
        function ajaxFunction( url )
        {
            var xmlHttp;
            try
            {
                // Firefox, Opera 8.0+, Safari
                xmlHttp = new XMLHttpRequest();    // 实例化对象
            }
            catch( e )
            {
                // Internet Explorer
                try
                {
                    xmlHttp = new ActiveXObject( "Msxml2.XMLHTTP" );
                }
                catch ( e )
                {
                    try
                    {
                        xmlHttp = new ActiveXObject( "Microsoft.XMLHTTP" );
                    }
                    catch( e )
                    {
                        alert("您的浏览器不支持AJAX！");
                        return false;
                    }
                }
            }
            xmlHttp.onreadystatechange = function()
            {

                if( xmlHttp.readyState == 4 && xmlHttp.status == 200 )
                {
                    item = xmlHttp.responseText;

                    if(item == '99'){
                    	alert("亲,每个微信账号每天仅能抽一次奖哦 ... 谢谢支持!^^");
                        return false;
                    }
                    
                    runPrize(item)
                }
            }
            xmlHttp.open( "GET", url, true );
            xmlHttp.send( null );
        }


        var url = "http://www.yhuigo.com/member/getprize"
        function getPrize(userId)
        {
             if( '' != userId )
            {
                 ajaxFunction( url+"?userId="+userId);    // 注意在后缀.php之后加传值是先用?分隔再添加数据
            }
        }
    </script>
</head>


<body>
<header>
    <div class='navbar navbar-default navbar-fixed-top yh-header yh-m-color-bg'  >
            微信公众号: 优惠GO
           <div class="btn-group yhg-nav-btn">
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"  type="button"> <span class="icon-ellipsis-horizontal"></span></button>
            <ul class="dropdown-menu">
                <li><a href="#" data-toggle="modal" data-target = '#Mymodal'><i class='icon-qrcode'></i> 公众号二维码</a></li>
                <!-- <li><a href="#"><i class='icon-info-sign'></i> 意见反馈</a></li> -->
            </ul>
        </div>
    </div>
</header>
<div class='container yh-container' style="padding-left:0px;padding-right:0px;background:none">
<!--bg-->
<div class="yhg-lot-main">
    <div class="yhg-lot-bg-bt"></div>
    <div class="yhg-lot-bg"><img src="<?=$template_url;?>/../../html/image/lottery/lottery_bg.jpg" style="width:100%"></div>
    <div class="yhg-lot-gzwcp">
        <div class='yhg-log-wcpqr'><img src="<?=$template_url;?>/../../html/image/coupon_qr.jpg" width="95%" /></div>
        <img src="<?=$template_url;?>/../../html/image/lottery/lottery_discount.png" width="100%"/>
    </div>
    
    <div class="yhg-lot-gzwcp">
        <div class='yhg-log-wcgqr'><img src="<?=$template_url;?>/../../html/image/qr_code1.png" width="95%"/></div>
        <img src="<?=$template_url;?>/../../html/image/lottery/lottery_qr.png" width="100%"/>
    </div>

<!-- lucky-->
<div  align="center" style="margin-top:10px;">
    <img src="<?=$template_url;?>/../../html/image/coupon_word.png"  width="50%" style="z-index: 8;"/>
</div>


<!--coupon-->
<div style='padding:10px;'>
<img src="<?=$template_url;?>/../../html/image/1.png" id="shan-img" style="display:none;" />
<img src="<?=$template_url;?>/../../html/image/2.png" id="sorry-img" style="display:none;" />
<div class="banner">
    <div class="turnplate" style="background-image:url(<?=$template_url;?>/../../html/image/turntable-bg.png);background-size:100% 100%;">
        <canvas class="item" id="wheelcanvas" width="422px" height="422px"></canvas>
        <img class="pointer" src="<?=$template_url;?>/../../html/image/pointer.png"/>
    </div>
</div>
</div>

<div style="text-align:center;padding:10px;"><p>活动时间：2016年6月1日至2016年6月28日</p>
<p>活动门店：天虹深圳沙井/民治/创业/公明/松岗/双龙/龙华/坂田店</p>
</div>

<div style="text-align:center;padding:10px;">
    <table class="table table-bordered" style="margin:0px auto;width:80%;font-size:12px;">
        <tr><td>惊喜大奖!</td><td>价值约1000元的洗衣机1部</td></tr>
        <tr><td>优惠提醒奖！</td><td>碧浪特价优惠7折起(详询促销员)</td></tr>
    </table>
</div>

<div class="yhg-lot-rules">
    <img src="<?=$template_url;?>/../../html/image/lottery/lottery_activity.png"/>
    <ul style='padding:10px; text-align:left;font-size:1em;background:#FFE6C0;margin:0px 15px;border-radius:10px;'>
        <li>(1)在指定门店购碧浪产品满30元即可抽奖，每个微信账号每天仅能抽一次奖，次日可重新购买及抽奖，门店现场抽奖及兑奖;</li>
        <li>(2)洗衣机兑奖凭证：现场中奖页面截图、天虹当日购物小票（购碧浪产品30元起）、顾客姓名/联系电话/身份证复印件等信息;</li>
        <li>(3)兑奖洗衣机陈列于门店现场，顾客自行承担运输；活动门店洗衣机只有1部，先中先兑，兑完即止；奖品不改不退，不可兑换为现金，不开发票等；奖品有限，兑完即止，具体以店内促销员确认为准;</li>
        <li>(4) 活动最终解释权归于天虹门店碧浪活动执行商所有。
        
        </ul>
</div>


</div>
<div class="footer" style="background:#fff3c9;padding-bottom:10px;">
<p>深圳市八川科技有限公司 &copy 2016</p>
	<p><a href="http://www.miitbeian.gov.cn" target="_blank">粤ICP备15091098号</a></p>	
</div>
</div>
</body>
</html>