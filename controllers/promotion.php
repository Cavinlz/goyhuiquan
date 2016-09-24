<?php

/** 
 * @author Cavinlz
 * 
 */
class promotionController extends \Controller
{

    /**
     */
    public function lotteryOp ()
    {
        if(!WeChat::checkWXAuth(true))
        {
            loc_url(WeChat::getFullAuthAPI('PRIZE'));
        }
        
        $data['h5flag'] = 'zhuanpan';
        $this -> Output('../../html/lottery',$data);
    }
}

?>