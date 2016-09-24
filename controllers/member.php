<?php

/** 
 * @author Administrator
 * 
 */
class memberController extends \Controller
{

    /**
     */
    public function addphoneOp ()
    {
        $phone = $this -> router -> post('phone');
        
        $model = $this -> model('members');
        
        if(!$ukey = User::get_user_session_Ukey()) HttpResponse::setJsonOutput(array('code'=>GENERAL_ERROR_RETURN_CODE, 'msg'=>'Invalid User Session.'));
        
        if($model -> update_member_via_uid(User::get_user_session_Ukey(),array('mobile'=>$phone))){
           HttpResponse::setJsonOutput(array('code'=>GENERAL_SUCCESS_RETURN_CODE));
        }
        else{
            
            logg::system('Cant write mobile in User id of :'.User::get_user_session_Ukey());
            
            HttpResponse::setJsonOutput(array('code'=>GENERAL_ERROR_RETURN_CODE, 'msg'=>'系统繁忙，请稍后再试.'));
        }
    }
    
    
    public function getprizeOp()
    {
        $uid = $this -> router -> get('userId');
        
        if(empty($uid)) die('Invalid Request');
        
        $prize_arr = array(
                '0' => array('id'=>1,'prize'=>'惊喜大奖','v'=>2),
                '1' => array('id'=>3,'prize'=>'优惠提醒奖','v'=>800),
                '2' => array('id'=>2,'prize'=>'谢谢惠顾','v'=>99),
                '3' => array('id'=>4,'prize'=>'谢谢参与','v'=>99),
        );
        
        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }
        
        $rid = get_rand($arr); //根据概率获取奖项id
        
        $model = $this -> model();
        
        $model -> setTable('limit_visits');
        
        if($rs = $model -> check_record_exists('',array('openid'=>$uid))){
            
            $datetime = date('md',strtotime($rs['lasttime']));
            
            $currentTime = date('md',time());
            
            if($currentTime == $datetime){
                die('99');  //only once per day
            }else{
                $model -> delete('DELETE FROM `'.$model -> getTable().'` WHERE `id` = '.$rs['id']);
            }
        }
        
        /*
        $userArray = array(
                
                'openid'    =>  $uid,
                'prize'     =>  $rid
        );
        
        $event_details = json_encode($userArray);
        
        $array = array(
                'event_key' =>  'user_get_prize',
                'event_val' =>  $event_details,
                'datetime'  => datetime(),
                'handled'   =>  1,
                'sessionid' => session_id()
        );
        */
        $arrayEvent = array(
                'openid' =>  $uid,
                'lasttime'  => datetime(),
                'page_flag' =>$rid
        );
        
        //$model = $this -> model();
        
        if($model->insert($arrayEvent)){
            echo $rid;
        }
        else{
            echo 2;
        }
        
        
    }
}

?>