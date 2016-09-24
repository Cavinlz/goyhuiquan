<?php
/**
 * 卡券码的状态
 *
 */
function card_code_status($status = '')
{
    $val1 = array(
            
            'YHWARM'                 =>   '未被添加'  , 
            'NORMAL'                 =>  "正常"                  ,
            'CONSUMED'	             =>  "已核销"                ,
            'EXPIRE'                 =>  "已过期"                ,
            'GIFTING'                =>  "转赠中"      ,
            'GIFT_TIMEOUT'           =>  "转赠超时"      ,
            'DELETE'                 =>  "已删除"        ,
            'UNAVAILABLE'            =>  "已失效"
	    
    );
    
    $val2 = array(
    
            '1'                 =>   '未被添加'  ,
            '2'                 =>  "正常"                  ,
            '3'	             =>  "已核销"                ,
            '4'                 =>  "已过期"                ,
            '5'                =>  "转赠中"      ,
            '6'           =>  "转赠超时"      ,
            '7'                 =>  "已删除"        ,
            '8'            =>  "已失效",
            '9'            =>  "已领取"
	  
    );

    return (empty($status))?$val1:$val2[$status];
}

define('CARD_CODE_STATUS_WARM', '1');
define('CARD_CODE_STATUS_NORMAL', '2');
define('CARD_CODE_STATUS_CONSUMED', '3');
define('CARD_CODE_STATUS_EXPIRED', '4');
define('CARD_CODE_STATUS_GIFTING', '5');
define('CARD_CODE_STATUS_GIFT_TIMEOUT', '6');
define('CARD_CODE_STATUS_DELETE', '7');
define('CARD_CODE_STATUS_UNAVAILABLE', '8');
define('CARD_CODE_STATUS_PICKED_UP',9);



function card_code_types($key='')
{
	$val1 = array(
	        
		'CODE_TYPE_TEXT'           =>  "文本"                  ,
		'CODE_TYPE_BARCODE'	       =>  "一维码"                ,
	    'CODE_TYPE_QRCODE'         =>  "二维码"                ,
	    'CODE_TYPE_ONLY_QRCODE'    =>  "二维码无code显示"      , 
	    'CODE_TYPE_ONLY_BARCODE'   =>  "一维码无code显示"      ,
	    'CODE_TYPE_NONE'           =>  "不显示code和条形码类型"    
	        
	);
	
	return ($key)?$val1[$key]:$val1;
}


/**
 * 卡券背景颜色
 * 
 */
function card_bg_colors($key='')
{
    $val1 = array(
             
            'Color010'         =>  "Color010_#63b359"                  ,
            'Color020'	       =>  "Color020_#2c9f67"                ,
            'Color030'         =>  "Color030_#509fc9"                ,
            'Color040'         =>  "Color040_#5885cf"      ,
            'Color050'         =>  "Color050_#9062c0"      ,
            'Color060'         =>  "Color060_#d09a45"       ,
            'Color070'         =>  "Color070_#e4b138"       ,
            'Color080'         =>  "Color080_#ee903c"       ,
            'Color081'         =>  "Color081_#f08500"       ,
            'Color082'         =>  "Color082_#a9d92d"       ,
            'Color090'         =>  "Color090_#dd6549"       ,
            'Color100'         =>  "Color100_#cc463d"       ,
            'Color101'         =>  "Color101_#cf3e36"       ,
            'Color102'         =>  "Color102_#5E6671"       ,
	    
    );
    
    return ($key)?$val1[$key]:$val1;
}

function card_types($key='')
{
    $val1 = array(
             
            'GROUPON'           =>  "团购券"                  ,
            'CASH'	             =>  "代金券"                ,
            'DISCOUNT'         =>  "折扣券"                ,
            'GIFT'    =>  "兑换券"      ,
            'GENERAL_COUPON'   =>  "普通优惠券"      
	    
    );
    
    return ($key)?$val1[$key]:$val1;
}

function card_efficient_type($key = '')
{
    $val1 = array(
             
            'DATE_TYPE_FIX_TIME_RANGE' =>  "固定日期区"                  ,
            'DATE_TYPE_FIX_TERM'	   =>  "固定时长(自领取后按天算)"                
	    
    );
    
    return ($key)?$val1[$key]:$val1;
}

function event_types()
{
    $val1 = array(
             
            'card_pass_check'           =>  "卡券通过审核"                  ,
            'card_not_pass_check'	             =>  "卡券未通过审核"                ,
	  
    );
    
    return $val1;
}

function card_status($key='')
{
    $val1 = array(
             
            '1'             =>  "审核中"                  ,
            '2'	            =>  "未通过"                ,
            '3'             =>  "待投放"                ,
            '4'             =>  "已投放"      ,
            '5'             =>  "违规下架"
	  
    );

    return ($key)?$val1[$key]:$val1;
}
define('CARD_STATUS_NOT_VERIFY', 1);
define('CARD_STATUS_VERIFY_FAIL', 2);
define('CARD_STATUS_VERIFY_OK', 3);
define('CARD_STATUS_DISPATCH', 4);
define('CARD_STATUS_USER_DELETE', 6);

function get_brand_list(Controller $controller)
{
    CMemory::load_cache_file('data/brands');

    if(!$results = CMemory::get('brands'))
    {
        $model = $controller -> model('brands');
        $rs = $model -> get_brands_list();

        $results =  array();

        if($rs){
            foreach ($rs as $val)
            {
                $results[$val['id']] = $val['brand_name'];
            }
        }
    }

    return $results;
}

function get_activity_list(Controller $controller)
{
    $model = $controller -> model('activities');
    $rs = $model -> get_act_list();

    $results =  array();

        if($rs){
            foreach ($rs as $val)
            {
                $results[$val['id']] = $val['act_name'];
            }
        }

    return $results;
}


function get_cards_total_capacity($cardid)
{
    $ctrl = Console::getInstance('controller');
    
    $model = $ctrl -> model('cardcodes');
    
    return $model -> get_available_codes_counts($cardid);
}

function get_cards_avail($cardid)
{
    $ctrl = Console::getInstance('controller');

    $model = $ctrl -> model('cardcodes');

    return $model -> get_available_get_counts($cardid);
}

function get_card_categories($key = '')
{
    $ctrl = Console::getInstance('controller');
    
    $model = $ctrl -> model('categories');
    
    if($rs = $model -> get_categories())
    {
        $val1 = array();
        foreach ($rs as $val)
        {
            $val1[$val['id']] = $val['cn_name'];
        }
    }
    
    return ($key)?$val1[$key]:$val1;
}

function get_card_codes_provider()
{
    $model = Console::getInstance('model');
    
    $model -> setTable('third_party');
    
    if($rs = $model -> select('*') -> query())
    {
        $val1 = array('0' => '-- 选择第三方券码供应商(若有) --');
        foreach ($rs as $val)
        {
            $val1[$val['eng_flag']] = $val['cn_name'];
        }
    }
    
    return $val1;
    
    
}


function check_userimg($imgurl)
{
    if(!$imgurl){
        $url = CFactory::getApplicationTemplateURL().DS.'images'.DS.'userface.png';
    }
    else
        $url = $imgurl;
    return $url;
}

function get_card_info_via_wcp($wechat_card_id)
{
    if(!$rs = CMemory::mc_get('cardinfo_'.$wechat_card_id))
    {
        //logg::debug('cardinfo'.$wechat_card_id.' key not exist');
        $rs = WeChat::getCardDetailsInfo($wechat_card_id);
    
        CMemory::mc_set('cardinfo_'.$wechat_card_id, $rs);
    }
    return $rs;
}

function get_card_baseinfo($wechat_card_id, $cardid='')
{
    $rs = get_card_info_via_wcp($wechat_card_id);
    
    $card_basic_info = $rs['card'][strtolower($rs['card']['card_type'])]['base_info'];
    
    return $card_basic_info;
}

function get_brand_total_likes($brandid, $init=0)
{
    $model = Console::getInstance('model');
    
    $rs = $model -> cleanUp() -> setTable('brands_likes') -> select('count(*) as total') -> where(array('bid'=>$brandid)) -> getOne();
    
    return ($rs['total'] + $init);
}

function check_user_like_brand($openid, $brandid)
{
    $model = Console::getInstance('model');
    
    return $model -> cleanUp() -> setTable('brands_likes') -> select('1') -> where(array('bid'=>$brandid,'openid'=>$openid)) -> getOne();
}


function user_all_brand_likes($openid, $setvalue = '')
{
    
    if(!empty($setvalue)){
        CMemory::mc_set('brandlikes_'.$openid, $setvalue,0 , 300);
        return;
    } 
    
    if(!$rs = CMemory::mc_get('brandlikes_'.$openid))
    {
        $rs = array();
        $model = Console::getInstance('model');
        $result = $model -> cleanUp() -> setTable('brands_likes') -> select('bid') -> where(array('openid'=>$openid)) -> query();
        
        if($result) {
            foreach ($result as $val)
            {
                if(!in_array($val['bid'], $rs))
                    $rs[] = $val['bid'];
            }
            CMemory::mc_set('brandlikes_'.$openid, $rs, 0 , 300);
        }
       
    }
    
    return $rs;
}



function get_rand($proArr) {
    $result = '';

    //概率数组的总概率精度
    $proSum = array_sum($proArr);

    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);

    return $result;
}