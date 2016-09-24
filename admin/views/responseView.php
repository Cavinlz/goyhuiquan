<?php
/**
 *
 * @author Cavinlz
 *        
 */
class responseView extends \FormView
{

    protected $failure = false;
    protected $success = false;
    protected $warning = false;
    protected $message = null;
    
    public function config_form_fieldsets()
    {
        CLanguage::load_lang_file('response');
        
        $this -> data['page_btn_group'] = array();
        
        array_push($this -> data['page_btn_group'], array('icon'=>'home','txt'=>CLanguage::Text('RSP.BACKHOME'),'link'=>'javascript:history.go(-1)'));
        
        if($this ->  success == true)
        {
            $info = array(
            
                    'title'		      =>	CLanguage::Text('RSP.SUCC')			   ,
                    'subtitle'		  =>	$this -> message                 ,
                    'icon'            =>    'smile'
            );
        }
        elseif($this ->  failure == true)
        {
            $info = array(
            
                    'title'		      =>	CLanguage::Text('RSP.ERR')			   ,
                    'subtitle'		  =>	$this -> message                 ,
                    'icon'            =>    'frown'
            );
            array_push($this -> data['page_btn_group'], array('icon'=>'chevron-sign-left','txt'=>CLanguage::Text('RSP.GOBACK'),'link'=>'javascript:history.go(-1)'));
        }
        elseif($this ->  warning == true)
        {
            $info = array(
        
                    'title'		      =>	CLanguage::Text('RSP.WARNING')			   ,
                    'subtitle'		  =>	$this -> message                 ,
                    'icon'            =>    'warning-sign'
            );
        }
        else 
        {
            $info = array(
            
                    'title'		      =>	CLanguage::Text('RSP.ERRCODE')			   ,
                    'subtitle'		  =>	$this -> message                 ,
                    'icon'            =>    'warning-sign'
            );
            array_push($this -> data['page_btn_group'], array('icon'=>'chevron-sign-left','txt'=>CLanguage::Text('RSP.GOBACK'),'link'=>'javascript:history.go(-1)'));
        }
        
        if($info)
        array_push($this -> form_fieldsets, $info);
    }
    
    public function set_response_data($msg, $code = GENERAL_SUCCESS_RETURN_CODE)
    {
        switch ($code)
        {
            case GENERAL_SUCCESS_RETURN_CODE:
                $this ->  success = true;
                break;
            case GENERAL_ERROR_RETURN_CODE:
                $this -> failure = true;
                break;
            case GENERAL_WARNING_RETURN_CODE:
                $this -> warning = true;
                break;
            default:
        }
        
        $this -> message = $msg;
        
    }
    
    

}

?>