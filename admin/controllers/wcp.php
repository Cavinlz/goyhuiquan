<?php

/** 
 * @author Administrator
 * 
 */
class wcpController extends \Controller
{

    /**
     */
    public function menucreateOp ()
    {
        
        $paramAray['button'][] = array(
                
                        "type"=>"view",
                        "name"=>"点我领券",
                        //"key"=>"YHG_CLICKME_GET_CARD",
                        "url"=>"http://www.yhuigo.com/"

        );
        
       //关键,去掉默认的Json转化中将中文转成/uxxx的默认设置
        $jsonArray = json_encode($paramAray,JSON_UNESCAPED_UNICODE);
        
        $rs = WeChat::createCustomMenuAPI($jsonArray);
        
        if($rs['errcode'] > 0)
        {
            $this -> redirect_rsp_page($rs['errmsg'].'['.$rs['errcode'].']',GENERAL_ERROR_RETURN_CODE);
        }
        
        $this -> redirect_rsp_page($rs['errmsg'],GENERAL_SUCCESS_RETURN_CODE); 
        
    }
}

?>