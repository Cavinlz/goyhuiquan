<?php
if ( defined( "CZCool" )  or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class Controller {
	protected $tpl_params = array();
	protected $_action = null;
	public $_myname = null;
	protected $_model = null;
	protected $_view = null;
	protected $router = null;
	/**
	 */
	public function __construct() {
		//identify the current controller name
		$this -> router = $this ->load('router');
		$this ->_myname = $this -> router -> route_url['controller'];
	
	}
	
	/**
	 * Get the specific model if exists
	 * @param model name: $model
	 * @return object
	 */
	final public function model($model='')
	{
		if(empty($model))
		{
			/**
			 * If the by pass param is empty normally it is calling the controller self model (with same prefix name)
			 * 
			 */
			if(is_object($this->_model)){
				return $this->_model;
			}
		}
		
		//the pre-defined model format {name}Model
		$model = $model.C('system.model_cls_suffix');
		$model = new $model;
		
		/**
		 * @tutorial: this class buid-in member $_model is only assigned once , in which its name is same as the controller name 
		 */
		if(!$this->_model){
			$this->_model = $model;
		}
		
		return $model;
	}
	
	final public function view($view='' , $return = true)
	{
		if(empty($view))
		{
			/**
			 * If the by pass param is empty normally it is calling the controller self model (with same prefix name)
			 *
			 * Otherwise , calling the system default view
			 */
			if(is_object($this->_view)){
				return $this->_view;
			}

		}
		
		$view = $view.C('system.view_cls_suffix');
		//the pre-defined model format {name}Model
		$view = new $view;
		
		if(is_object($view)){
			$this->_view = $view;
		}
		
		if($return)
			return $view;
	}
	
	final protected function load($lib)
	{
		return Console::getInstance($lib);
	}
	
	/**
	 * Get System View 
	 * @param Path of the View:  $path
	 * @param Required Data to be output in the template : array $data
	 */
	protected function Output($path='', $data = array())
	{
		/**
		 * @tutorial: this is used the console viewer which to determine what to display and to display in which style of template
		 * 
		 * the console viewer can be changed at everywhere so that it is flexible to control anywhere
		 */
		$view = $this -> view();
		$view ->init($path, $data);
		$view ->display();
	}
	
    public function redirect_rsp_page($msg, $code = '503')
    {
        if($code != GENERAL_SUCCESS_RETURN_CODE)
            logg::write($msg, $code);
        
        $view = $this -> view('response');
        $view -> set_response_data($msg,$code);
        $this -> Output('response');
        die();
    }
}

?>