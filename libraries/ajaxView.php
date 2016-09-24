<?php
/**
 *
 * @author Cavinlz
 *        
 */
class ajaxView extends View {
	
	public function init($template='', $config = array())
	{
	
		if($template!='')
		{
			$this ->_template = Template_Path.DS.'ajax_tpl'.DS.$template.'.tpl.php';
				
			if(!file_exists($this->_template))
			{
				logg::system("Could not find template in :".$this->_template);
				die(err('PRGException.msg'));
			}
		}
	
		$this -> data = $config;
		
		$this -> load_form();
		$this -> router = $this -> load('router');
	}
	
	public function display()
	{

		ob_start();
		$this -> loadSystemTemplates();
		$flush_content = ob_get_contents();
		ob_end_clean();
		$this -> _output = $flush_content;
	
		HttpResponse::setOutput($this ->_output);
	}
	
	
	protected function loadSystemTemplates()
	{
		if(is_array($this->data))
			extract($this->data);
	
		require $this->_template;
	}

	
}

?>