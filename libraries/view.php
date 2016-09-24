<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class View {
	public $_header = null;
	protected $_template = null;
	public $form = null;
	public $data;
	public $_output = null;
	protected $template_default = array();
	protected $is_display_header = true;
	protected $is_display_footer = true;
	protected $router = null;
	/**
	 * Config whether the has the side bar
	 * - True -> hv otherwise hvnt
	 * @var unknown
	 */
	protected $is_display_sidebar = true;
	
	protected $is_loadnav = true;
	
	/**
	 * each page template must contains a page id (for JM usage only)
	 *
	 * @var unknown
	 */
	protected $pageid = null;
	/**
	 *  Detemine the distribution of the columns
	 *  Total 12 columns 
	 *  --------------------------------
	 *  |             - Header-      	|
	 *  |SideBar| |		 Main		|
	 *  |        		- Footer-      		|
	 *  --------------------------------
	 * @var unknown
	 */
	protected $gird_column_main = '10';
	protected $gird_column_sidebar = '2';
	
	protected $module_name = null;
	
	/**
	 */
	function __construct(){
	}
	
	public function init($template='', $config = array())
	{
		
		if($template!='')
		{
			$this ->_template = Template_Path.DS.$template.'.html.php';
			
			if(!file_exists($this->_template))
			{
				logg::system("Could not find template in :".$this->_template);
				die(err('PRGException.msg'));
			}
		}
		
		$this -> data = $config;
		$this -> router = $this -> load('router');
	}
	
	
	public function get_model()
	{
		return $this->load('model');
	}
	
	/**
	 * @tutorial OutPut the page (Main Function to parse required data)
	 * 
	 * @since v1.0
	 */
	public function display()
	{
	    $this -> configSystemTemplates();
		ob_start();
		$this -> loadSystemTemplates();
		$flush_content = ob_get_contents();
		ob_end_clean();
		$this -> _output = $flush_content;
		
		echo $this ->_output;
	}
	
	/**
	 * @tutorial Additional ToolBar to difined here in order to attached to the Sidebar if any (like Search Form, etc)
	 * 
	 * @since v1.0
	 */
	public function addToolBar()
	{
		
	}
	
	/**
	 * @tutorial  Load the mandatory system libaries
	 *
	 */
	protected function loadSystemTemplates()
	{
		if(is_array($this->data))
			extract($this->data);
		
		foreach ($this ->template_default as $key => $val)
		{
		    if(!strpos($val, '.php'))
			    $file = $val.'.php';
		    else
		        $file = $val;
		    
			if(!file_exists($file))
			{
				logg::system("Could not find template file ".$file);
				unset($this ->template_default[$key]);
				continue;
			}
			require $file;
		}
	}
	
	/**
	 * @tutorial Define the system required templates by sequence
	 * 
	 * @since v1.0
	 */
	protected function configSystemTemplates()
	{
		$this ->template_default = array(
				'header' => Template_Path.DS.'header',
				'body'=> Template_Path.DS.'main',
				'footer' => Template_Path.DS.'footer'
		);
	}
	
	protected function f($file)
	{
		return $this ->template_default[$file];
	}
	
	/**
	 * @tutorial Get the user defined template which used in the html file
	 * 
	 * @return string: the template path
	 * 
	 * @since v1.0
	 */
	public function get_template()
	{
		return $this->_template;
	}
	
	/**
	 * @tutorial Get Html Page Title
	 * 
	 * @since v1.0
	 */
	public function get_html_title()
	{
		return CFactory::getApplicationSubject();
	}
	/**
	 * @tutorial Get Html Charset
	 * 
	 * @since v1.0
	 */
	public function get_html_charset()
	{
		return CFactory::getApplicationCharset();
	}
	
	public function get_html_stylesheet()
	{
		$stylesheet = CFactory::getApplicationStyleSheet();
		
		if($stylesheet)
		{
			foreach ($stylesheet as $val)
			{
				echo '<link href="'.$val.'" rel="stylesheet">';
			}
		}
	}
	
	/**
	 * @tutorial This is to load the spec js file via an js model name , which defined in the requirejs configuration
	 * 
	 * @param unknown $model - The js model which requires to be loaded 
	 * @param string $object - The ID of the script element which to be located 
	 * 
	 * @author Cavinlz
	 * 
	 * @version 1.0
	 */
	public function load_js_model($model, $object = 'jsmodel', $properties = array())
	{
		$datas = '';
		if(!empty($properties)) 
		{
			foreach($properties as $key => $attribute) {
				if(empty($key))continue;
				$datas .= ' data-' . $key;
				if($attribute !== "")
					$datas .= ' = "' . $attribute . '"';
			}
		}
		
		echo '<script id = "'.$object.'" data-model = "'.$model.'" "'.$datas.'"></script>';	
	}
	
	public function get_template_url()
	{
		return CFactory::getApplicationTemplateURL();
	}
	
	public function get_login_username()
	{
		return $_SESSION['TRANS_USER_NAME'];
	}
	
	public function load_template($tpl)
	{
		if(file_exists($tpl)) require $tpl;
	}
	
	public function load_sidebar()
	{
		$file = Template_Path.DS.'sidebar.php';
		$this ->load_template($file);
	}
	
	public function get_sidebar_column()
	{
		return $this ->gird_column_sidebar;
	}
	
	public function set_sidebar_column($cols)
	{
		$this ->gird_column_sidebar = $cols;
	}
	
	public function get_mainbar_column()
	{
		return $this ->gird_column_main;
	}
	
	public function set_mainbar_column($cols)
	{
		$this ->gird_column_main = $cols;
	}
	
	public function has_sidebar()
	{
		return $this -> is_display_sidebar;
	}
	
	protected function load_form()
	{
		require_once LibPath.'/plugins/CForm/Form.php';
	}
	
	final public function load($lib)
	{
		return Console::getInstance($lib);
	}
	
	public function get_module_name()
	{
		return $this -> module_name;
	}
	
	public function load_nav()
	{
		if($this -> is_loadnav)
			require Template_Path.DS.'nav.php';
	}
	
	/**
	 * each page template must contains a page id (for JM usage only)
	 *
	 * @var unknown
	 */
	
	public function get_pageid($return = false)
	{
		if($return)
			return $this -> pageid;
	
		echo $this -> pageid;
	}
	
	public function get_pageheader($header = '')
	{
	    if($header) $this ->_header = $header;
	    
	    return $this ->_header;
	}
}

?>