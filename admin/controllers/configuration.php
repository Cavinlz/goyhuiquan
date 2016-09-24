<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class configurationController extends \Controller
{    
    public function indexOp()
    {
    	$model = $this -> model();
    
    	//$configs = array('sys_config','admin_config','web_config');
    
    	$rs = $model -> get_configs();
    
    	if(!empty($rs))
    	{
    		$i = 0;
    		foreach ($rs as $val)
    		{
    			$i++;
    			if($i == 1){
    				$current_key = $data['curr_key'] = $val['config_key'];
    				$current_val = unserialize($val['config_val']);
    			}
    			$data['configs'][] = $val;	 
    		}
    		$data['total'] = $i;
    	}
    
    	if($current_key){
    		
    		if($fields = $model -> get_configs_fields($current_key))
    		{
    			$view = $this -> load('view');
    			
    			$form = array(
    					'target' => "frobj",
    					'action' => $this -> router ->return_url($this -> router -> route_url['controller'],'save')
    			);
    			
    			$form_name = $current_key.'_form';
    			
    			$view -> form = new CForm($form_name, $form);
    			
    			foreach ($fields as $field)
    			{
    				$view -> form_append($field, $current_val);
    			}
    			
    			$view -> form
    			-> addElement(new CHidden('','formobj',array('value'=>$form_name)))
    			-> addElement(new CHidden('','config_type',array('value'=>$current_key)))
    			-> addElement(new CButton(CLanguage::Text('BUTTON.SUBMIT'),'submitBtn','submit',array('icon'=>'ok')))
    			-> addElement(new CButton(CLanguage::Text('BUTTON.RESET'),'reset','reset', array('id'=>'resetBtn','icon'=>'undo')));
    			
    			//$ele = $view ->form-> getElements();
    		}    	
    		
    	}
    	//echo json_encode(array());
    	CFactory::addApplicationStyleSheet(CFactory::getApplicationTemplateURL().'/styles/bootstrap-switch.css');
    
    	$this -> Output('config',$data);
    }
    
    
    /**
     * @tutorial Save the configuration and build the cache setting file
     * 
     */
    public function saveOp()
    {
    	$router = $this -> load('router');
    	
    	$config_type = $router -> fetch('config_type');
    	
    	$prefixArray = explode('_', $config_type);
    	
    	$prefix = $prefixArray[0];
    	
    	$code = GENERAL_WARNING_RETURN_CODE;
    	
    	if($config_type == 'sys_config' || $config_type == 'admin_config' || $config_type == 'web_config')
    	{
    	    /**
    	     * The form fields' prefix is same as its owner prefix
    	     * Like , {admin}_debug  is under {admin}_config
    	     * 
    	     */
    	    foreach ($_POST as $key => $val)
    	    {
    	       if(strpos($key, $prefix) === false)  continue;
    	       $configs[$key] = $val;
    	    }
    	    
    	    if(empty($configs))
    	    {
    	        $msg = CLanguage::Text('RETURN.SAVE_FAILED');
    	    }
    	    elseif( $this ->model() -> save_system_config($configs, $config_type) )
    	    {
    	    	$msg = CLanguage::Text('RETURN.SAVE_SUCCESS');
    	    	$code = GENERAL_SUCCESS_RETURN_CODE;
    	    }
    	    else
    	    {
    	        $msg = CLanguage::Text('RETURN.SAVE_FAILED');
    	        $code = GENERAL_ERROR_RETURN_CODE;
    	    }
    	}

    	
    	if($code == GENERAL_SUCCESS_RETURN_CODE)
    	{
    	    /**
    	     * This is to rebuild the cache config file
    	     */
    		CMemory::build_cache_file($router -> route_url['controller'].DS.$config_type, $config_type, $configs);
    	}
    	
    	$js_func_params = array($router->fetch('formobj'),$msg,$code);
    	
    	HttpResponse::jsPrompt($js_func_params);

    }
    
    public function getconfigOp()
    {
    	$current_key = $this -> router -> post('k');
    	
    	$model = $this -> model();
    	
    	if($current_key)
    	{
    		
    		$rs = $model -> get_configs($current_key);
    		$current_val = unserialize($rs['config_val']);
    	
    		if($fields = $model -> get_configs_fields($current_key))
    		{
    			//get owner viewer
    			$view = $this -> view();
    			 
    			$form = array(
    					'target' => "frobj",
    					'action' => $this -> router ->return_url($this -> router -> route_url['controller'],'save')
    			);
    			 
    			$form_name = $current_key.'_form';
    			 
    			$view -> form = new CForm($form_name, $form);
    			 
    			foreach ($fields as $field)
    			{
    				$view -> form_append($field, $current_val);
    			}
    			 
    			$view -> form
    			-> addElement(new CHidden('','formobj',array('value'=>$form_name)))
    			-> addElement(new CHidden('','config_type',array('value'=>$current_key)))
    			-> addElement(new CButton(CLanguage::Text('BUTTON.SUBMIT'),'submitBtn','submit',array('icon'=>'ok')))
    			-> addElement(new CButton(CLanguage::Text('BUTTON.RESET'),'reset','reset', array('id'=>'resetBtn','icon'=>'undo')));
    			 
    			//$ele = $view ->form-> getElements();
    		}
    		
    		//reset the console's viewer to control the display
    		self::set_ajax_view();
    		 
    		$console_viewer = $this -> load('view');
    		 
    		$console_viewer -> form = $view -> form;
    	
    	}
    	
    	
    	
    	$this -> Output('configs/general',$data);
    	
    }
    
    
    
    protected function set_ajax_view()
    {
    	Console::setNewSystemLibary('view',$this -> view('ajax'));
    }
    
}

?>