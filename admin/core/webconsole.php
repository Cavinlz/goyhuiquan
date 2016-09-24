<?php

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
		if(isset($command['controller']))
		{
			$controller = $model = $view = $command['controller'];
			$ctrl_file = Controller_Path.DS.$controller.'.php';
			$mod_file = Model_Path.DS.$model.'Model.php';
			$view_file = View_Path.DS.$view.'View.php';
		}
		else
		{
			$controller = $model = $view =  C('system.ctrler_default');
			$ctrl_file = Controller_Path.DS.$controller.'.php';
			$mod_file = MODEL_PATH.DS.$model.'.php';
		}
		

		/**
		 * Define the Action
		 */
		if(isset($command['action']))
		{
			$act = $command['action'];
			$action = $act.C('system.action_suffix');
		}
		else
		{
			$act = C('system.action_default');
			$action = $act.C('system.action_suffix');
		}
		
		/**
		 * For those invalid entry redirect to login page accordingly
		 *
		 * unless the public api entrance
		 */
		if($controller != 'login' && !self::getInstance('session') -> chkAdmUserLogin())
		{
		    if($controller != 'api'){ // public API flag
		        loc_url($router->return_url('login','',array('return_url'=>urlencode($router->return_url($controller,$act)))));
		    }
		}
		
		
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
		
		
		$current = $controller.DS.$router->route_url['action'];
		
		
		
		if(isset($command['params']))
		{
			$params = $command['params'];
		}
		
		if(file_exists($ctrl_file))
		{
			require $ctrl_file;
			//the pre-defined controller format {name}Controller
			$controlObj = $controller.C('system.ctrler_cls_suffix');
			$controller = new $controlObj;
			
			self::setNewSystemLibary('controller', $controller);
			
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
					$controller -> redirect_rsp_page(CLanguage::Text('模块开发正在紧张进行中,尚未对外开放 ...'),GENERAL_WARNING_RETURN_CODE);
				}
			}
			else
			{
			    
				logg::system("Could not identify action: ".$action);
				$controller -> redirect_rsp_page(CLanguage::Text('模块开发正在紧张进行中,尚未对外开放 ...'),GENERAL_WARNING_RETURN_CODE);
				//loc_url(self::getInstance('router') -> return_url('hey','err500'));
			} 
			
		}
		else
		{
		    $controller = new Controller();
			logg::system("Could not find controller in ".$ctrl_file);
			$controller -> redirect_rsp_page(CLanguage::Text('模块开发正在紧张进行中,尚未对外开放 ...'),GENERAL_WARNING_RETURN_CODE);
			//loc_url(self::getInstance('router') -> return_url('hey','err500'));
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
				'model' 	 => LibPath.DS.'model'	   ,
				'view' 		 => LibPath.DS.'view'      ,
				'router' 	 => LibPath.DS.'router'	   ,
				'language' 	 => LibPath.DS.'language'  ,
				'mydb'		 => LibPath.DS.'mysql.db'  ,
				'session'	 => LibPath.DS."session"   ,
				'memory'	 => LibPath.DS.'memory'    ,
		        'wechat'	 =>	LibPath.DS.'wechat',
			//	'user'	 	 => BasePath.DS."function".DS.'user',
			//	'purview'	 =>	BasePath.DS."function".DS.'purview'
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