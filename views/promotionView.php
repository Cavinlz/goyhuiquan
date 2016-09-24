<?php

/** 
 * @author Administrator
 * 
 */
class promotionView extends \View
{

    /**
     */
    public function __construct ()
    {
        parent::__construct();
    }
    
    
    protected function configSystemTemplates()
    {
        switch($this -> data['h5flag'] )
        {
            case 'zhuanpan':
                $this ->template_default = array(
                            'body'=> $this ->_template
                        );
                        break;
            default:
        
                parent::configSystemTemplates();
        
        }
    }
}

?>