<!-- banner-->
<div id="myCarousel" class="carousel slide">
    <!-- 轮播（Carousel）指标 -->
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <!--  <li data-target="#myCarousel" data-slide-to="2"></li>-->
    </ol>
    <!-- 轮播（Carousel）项目 -->
    <div class="carousel-inner">
        <div class="item active">
            <img src="<?php echo $template_url;?>/images/20160601_2.jpg" alt="First slide" style="width: 100%; height: 100%"/>
        </div>
        
        <div class="item">
            <img src="<?php echo $template_url;?>/images/20160601_1.png" alt="Second slide" style="width: 100%; height: 100%"/>
        </div>
         <!-- 
        <div class="item">
            <img src="<?php echo $template_url;?>/images/20160601_3.png" alt="Third slide" style="width: 100%; height: 100%"/>
        </div>
        -->
    </div>
    <!-- 轮播（Carousel）导航 -->
    <a class="carousel-control left" href="#myCarousel" data-slide="prev">‹</a>
    <a class="carousel-control right" href="#myCarousel" data-slide="next">›</a>
</div>
<div class='alert alert-warning text-center yh-alert'>
点击领取优惠劵，使用时请从"微信→我→卡券"中查找并展示
</div>
<!-- 
<div class='btn-group-justified yh-filter'>
        <div class="btn-group">
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"  type="button">默认排序 <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="#">超市距离</a></li>
                <li><a href="#">领取数量</a></li>
                <li><a href="#">折扣力度</a></li>
            </ul>
        </div>
        <div class="btn-group">
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"  type="button">默认排序 <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="#">超市距离</a></li>
                <li><a href="#">领取数量</a></li>
                <li><a href="#">折扣力度</a></li>
            </ul>
        </div>
        <div class="btn-group">
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"  type="button">默认排序 <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="#">超市距离</a></li>
                <li><a href="#">领取数量</a></li>
                <li><a href="#">折扣力度</a></li>
            </ul>
        </div>
        <div class="btn-group">
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"  type="button">默认排序 <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="#">超市距离</a></li>
                <li><a href="#">领取数量</a></li>
                <li><a href="#">折扣力度</a></li>
            </ul>
        </div>
</div>
 -->
<section class='yh-cards' style="padding-bottom:10px;">
<script>
var timeArray = new Array();
var cardArray = new Array();
var getgeo = true;
<?php 
    if(!defined('NO_GEO_LOCATION')):
?>
getgeo = false;
<?php 
    endif;
?>
</script>
<?php 
if($cards):
    $counter = 0;
    foreach ($cards as $val):
        $discount = false;
        $basiinfo = get_card_baseinfo($val['wechat_card_id']);
        $imgpath = '';
        $likes = get_brand_total_likes($val['brand_id'],$val['likes']);
        $codes_count = get_cards_total_capacity($val['id']);
        $codes_get = get_cards_avail($val['id']);
        $remaintims = $val['end_timestamp'] - time();
        $end_date = date('Y-m-d H:i:s', $val['end_timestamp']);
        
        $likebtnid = 'like';
        $likeicon = 'heart-empty';

        if($openid = User::get_user_session_Openid()){
            
            $getAllUserLikes = user_all_brand_likes($openid);
            
            if(is_array($getAllUserLikes)){
                if(in_array($val['brand_id'], $getAllUserLikes)){
                    $likebtnid = 'unlike';
                    $likeicon = 'heart';
                }
            }
            
        }
        
        
        //$days=round($remaintims/3600/24);
        
        if(preg_match_all("/^\d{1,3}/", $basiinfo['title'],$matches)){
            $num = $matches[0][0];
            if($num < 10){
                $basiinfo['title'] = str_ireplace($num, '<span class="discount_num_1">'.$num.'</span><span class="discount_title_1">', $basiinfo['title']);
            }elseif($num > 100){
                $basiinfo['title'] = str_ireplace($num, '<span class="discount_num_100">'.$num.'</span><span class="discount_title_100">', $basiinfo['title']);
            }else{
                $basiinfo['title'] = str_ireplace($num, '<span class="discount_num_10">'.$num.'</span><span class="discount_title_10">', $basiinfo['title']);
            }
            
            
            $basiinfo['title'].='</span>';
        } 
        
        $ctrl = Console::getInstance('controller');
        $whoGotCodes = $ctrl -> model('cardcodes') -> get_who_got_codes($val['wechat_card_id'], 2);
        
?>
<script>
timeArray.push(<?php echo strtotime($end_date)?>);
cardArray.push("<?php echo $val['wechat_card_id']?>");
</script>
<div class='yh-card-list'>
<div class='rows'>
    <div class='col-xs-2 col-md-2 yhg-float-img' id='timer-<?php echo $counter?>'>
        <a href='#' class='thumbnail'> <img src="<?=$this -> router -> return_url('brands','getimg',array('s'=>$val['brand_id'],'n'=>base64_encode($val['imgurl'])))?>" title='<?php echo $val['imgurl'] ?>' style="width:100%;"></a>
    </div>
    <div class='fade'><?php  echo $price;?></div>
    <div class='col-xs-3 col-md-3 yhg-float-cardinfo'>
        <span style=" margin-top: 0" class="discount_title">
                                         <h4 class='yhg-cardtitle' style='color:#FC3;position: relative;'><?php echo $basiinfo['title']?><small class='yhg-sub-title'><?php echo $basiinfo['sub_title']?></small></h4>
                                         
                                    </span>
    </div>
    <div class='col-xs-4 col-md-4 yhg-float-timer'>
        <span class='yh-counter'><i class="icon-time"></i> <label id="RemainD-<?php echo $counter?>"></label><label id="RemainH-<?php echo $counter?>">00</label>:<label id="RemainM-<?php echo $counter?>">00</label>:<label id="RemainS-<?php echo $counter?>">00</label></span>
        <span class='yh-quantity'><?php echo ($codes_get)?>/<?php echo $codes_count ?></span>
        <div class='yh-userfaces'>
            <img src="<?php echo check_userimg($whoGotCodes[0]['imgurl']);?>" class="user_head_icon"/> 
            <img src="<?php echo check_userimg($whoGotCodes[1]['imgurl']);?>" class="user_head_icon"/>
            <div class='clearfix'></div>
        </div>
        
    </div>
    <div class='col-xs-2 col-md-2 yhg-float-like yh-m-color-bg'>
       <span class='yhg-heart'><button class="btn yhg-likebtn" id='<?=$likebtnid?>-<?php echo $val['brand_id']?>-<?php echo $val['id']?>'><i class="icon-<?=$likeicon ?>"></i></button></span>
       <span class='yhg-heart-counter' style='font-size:1.2em'><label id="like-stat-<?php echo $val['brand_id']?>-<?php echo $val['id']?>"><?php echo $likes?></label></span>
       <span class='yhg-heart-counter' style='font-size:0.8em'>信赖品牌</span>
    </div>
    <div class='clearfix'></div>
</div>

<hr>
        <div class='yh-instruction'>
            <div class='' style="width:78%;">
                <blockquote>
                                            <p>使用说明：<p/>
                            <?php echo subString(nl2br($basiinfo['description']),0,108);?>
               </blockquote>
            </div>
            <button type='button' class='btn yh-btn-100'  data-card='<?php echo $val['wechat_card_id']; ?>' id='add-card-<?=$val['id']?>'>马上领取 <i class='icon-double-angle-right'></i></button>
        </div>

</div>

<?php 
$counter++;
    endforeach;
    
endif;
?>

                                    <div id="FloatDIV" name="FloatDIV" style="position: absolute;z-index: 8">
                                    <div style="position: absolute"><img src="<?php echo $template_url?>/images/lucky_access_close.png" width="20" height="20" onclick="hiddentLuckyAccess()"/></div>
                                     <a href="http://www.yhuigo.com/promotion/lottery"><img src="<?php echo $template_url?>/images/lucky_access.png" width="100px" height="131px" /></a></div>


                                    <script type="text/javascript" >
                                        var Marginleft = 0;    //浮动层离浏览器右侧的距离
                                        var MarginTop = document.documentElement.clientHeight- 200;   //浮动层离浏览器顶部的距离
                                        var Width = 100;            //浮动层宽度
                                        var Heigth= 131;           //浮动层高度
                                        var timeout;
                                        //设置浮动层宽、高
                                        function Set()
                                        {
                                            document.getElementById("FloatDIV").style.width = Width + 'px';
                                            document.getElementById("FloatDIV").style.height = Heigth + 'px';
                                        }
                                        //实时设置浮动层的位置
                                        function Move()
                                        {
                                            var b_top = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
                                            var b_width= document.body.clientWidth;
                                            document.getElementById("FloatDIV").style.top = b_top +MarginTop   + 'px';
                                            document.getElementById("FloatDIV").style.left= b_width - Width - Marginleft + 'px';
                                            timeout = setTimeout("Move();",50);
                                        }
                                        Set();
                                        Move();
                                       // window.onscroll = function(){
                                        //  Move();
                                       // }
                                       function hiddentLuckyAccess()
                                       	{
                                       	       if(timeout!=null)
                                                   clearTimeout(timeout);

                                               document.getElementById("FloatDIV").style.display="none";
                                         }
                                    </script>
                                     
 <div class="modal fade" id="Mymodal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4>扫描下方二维码关注 “优惠GO” 公众号</h4>
                                        </div>
                                        <div class="modal-body" style="text-align:center">
                                              <div class='' style="margin:0px auto"><img src='<?php echo $template_url?>/images/qrcode.jpg' /></div>
                                        </div>
   </div>
                                    </div>


</section><?php $this -> load_js_model('home');?>