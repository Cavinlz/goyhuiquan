<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 * @since Dec 15, 2014
 * 
 */
final class Console 
{	
	//store all the libaries 
	public static $_lib = null;
	
	/**
	 */
	public static function init() 
	{
		//initialize the global factory resource
		CFactory::init();
		self::loadSystemLibaries();
	}
	
	public static function run()
	{
		self::init();
	
		$router = self::getInstance('router');
		
		$command = $router -> parseUrlString();
		
		/**
		 * Define the Controller & Model
		 */
		if($command['controller'])
		{
			$controller = $model = $view = $command['controller'];
			$ctrl_file = Controller_Path.DS.$controller.'.php';
			$mod_file = Model_Path.DS.$model.C('system.model_cls_suffix').'.php';
			$view_file = View_Path.DS.$view.C('system.view_cls_suffix').'.php';
		}
		else
		{
			$controller = $model = $view =  C('system.ctrler_default');
			$ctrl_file = Controller_Path.DS.$controller.'.php';
			$mod_file = Model_Path.DS.$model.C('system.model_cls_suffix').'.php';
			$view_file = View_Path.DS.$view.C('system.view_cls_suffix').'.php';
		}
		
		/**
		 * 只有部分页面可以通过非微信浏览器访问，需要跳过这个检查的controller需要添加到以下$filterController中
		 * {防止通过pc恶意访问 }
   		 *
		 * @since 04th Sep, 2016
		 */
		$filterController = array('events');
		/*
		if(!in_array($controller, $filterController))
		{
    		if(!defined('IS_WECHAT_BROWSER'))
    		{
    		    $template_url = CFactory::getApplicationTemplateURL();
    		    echo '<div style="width:380px;margin:50px auto">';
    		    echo '<h4 style="font-family:Microsoft YaHei">请扫描下方二维码关注 “优惠GO” 公众号</h4>';
    		    echo "<img src='".$template_url."/images/qrcode.jpg' style='margin:0px auto;'/>";
    		    echo '</div>';
    		    die();
    		}
		}
		*/
		
		/**
		 * Initialize the Cache Object, for future usage
		 */
		CMemory::init( array(
			'configuration/sys_config',
			'configuration/web_config',
		));
		

		/**
		 * Initialize the language package
		 */
		CLanguage::init($controller,(empty($command['lang']))? CMemory::get('web_config.web_lang'): $command['lang']);
		
		
	    $session = self::getInstance('session');
	    
	    /**
	     * Check whether it is firstly visited so as to fetch the user's location when needed
	     */
	    //echo $session -> data['YHG_MY_CURR_GEO'];
	    if(!$session -> data['YHG_MY_CURR_GEO']){
	        define('NO_GEO_LOCATION', true);
	    }

	    
		/**
		 * Define the Action
		 */
		if($command['action'])
		{
			$action = $command['action'].C('system.action_suffix');
		}
		else
		{
			$action = C('system.action_default').C('system.action_suffix');
		}
		
		if($command['params'])
		{
			$params = $command['params'];
		}

		
		if(file_exists($ctrl_file))
		{
			require $ctrl_file;
			//the pre-defined controller format {name}Controller
			$controlObj = $controller.C('system.ctrler_cls_suffix');
			$controller = new $controlObj;
			
			if(file_exists($mod_file)){
				//check if the model does exist
				require $mod_file;
				//reset real callable model
				self::setNewSystemLibary('model', $controller->model($model));
			}
			
			if(file_exists($view_file)){
				//check if the view does exist
				require $view_file;
				//reset real callable view
				self::setNewSystemLibary('view', $controller->view($view));
			}
			
			if($action)
			{
				if(method_exists($controller, $action))
				{
					isset($params)?$controller ->$action($params): $controller -> $action();
				}
				else
				{
					logg::system("Could not find controller function: ".$action);
					die(err('PRGException.msg'));
				}
			}
			else
			{
				logg::system("Could not identify action: ".$action);
				die(err('PRGException.msg'));
			} 
			
		}
		else
		{
			logg::system("Could not find controller in ".$ctrl_file);
			die(err('PRGException.msg'));
		}
		
	}
	
	/**
	 * @tutorial  Load the mandatory system libaries 
	 * 
	 */
	public static function loadSystemLibaries()
	{
		self::configSystemLibaries();
		
		foreach (self::$_lib as $key => $val)
		{
			$file =  $val.'.php';
			if(!file_exists($file))
			{
				logg::system("Could not find file ".$file);
				unset(self::$_lib[$key]);
				continue;
			}
			require_once ($file);
		}
	}

	public static function setNewSystemLibary($lib,$object)
	{
		self::$_lib[$lib] = $object;
	}
	
	/**
	 * @tutorial  Configure the mandatory system libaries here
	 * 
	 */
	public static function configSystemLibaries()
	{
		self::$_lib = array(
				'controller' => LibPath.DS.'controller',
				'model' 	 => LibPath.DS.'model',
				'view' 		 => LibPath.DS.'view',
				'router' 	 => LibPath.DS.'router',
				'language' 	 => LibPath.DS.'language',
				'mydb'		 => LibPath.DS.'mysql.db',
				'session'	 => LibPath.DS."session",
				'memory'	 => LibPath.DS.'memory',
				'wechat'	 =>	LibPath.DS.'wechat',
				'user'	 	 =>	LibPath.DS.'user'
		);
	}
	
	/**
	 * @tutorial Get specific system pre-loaded libary
	 * 
	 * @param String(Class Name - all in lower cases): $objname 
	 * 
	 */
	public static function getInstance($objname)
	{
		//print_r(self::$_lib);
		if(!array_key_exists($objname, self::$_lib))
		{
			logg::system("Could not find the System Object ".$objname);
			//die(err('PRGException.msg'));
			return false;
		}
		
		if(!is_object(self::$_lib[$objname]))
		{
			$lib = ucfirst($objname);
			self::$_lib[$objname] = new $lib;
		}
		
		return self::$_lib[$objname];
	}
}

?>