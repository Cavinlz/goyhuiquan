<?php

/** 
 * @author Administrator
 * 
 */
class cardsController extends \Controller
{

    /**
     */
    public function addcardOp ()
    {
        $jsapiTicket = WeChat::getCardApiTicket();
    }
}

?>