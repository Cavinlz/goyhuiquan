<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class TableView extends View {
	protected $my_records = null;
	protected $my_pages_vars = array();
	protected $my_table_headers = array();
	protected $my_helper_toolbar = null;
	
	
	public function init($template='', $config='')
	{
		parent::init($template, $config);
		$this -> config_table_headers();
		$this -> config_helper_toolbar();
	}
	
	public function display()
	{
		$this -> config_table_body();
		
		parent::display();
	}
	
	public function get_records($records = '')
	{
		if(empty($records)){
			
			//if(empty($this ->my_records)) $this ->my_records =  $this -> data;
			
			return $this ->my_records;
		}else{
			$this ->my_records = $records;
			
			if(empty($this ->my_pages_vars)){
			    $model = $this -> get_model();
			    $this ->my_pages_vars = $model -> get_pages_vars();
			}
			
		}
	}
	
	public function has_next()
	{
		return next($this->my_records);
	}
	
	public function get_current($returnObj = true)
	{

		return ($returnObj === true) ? (object)current($this->my_records): current($this->my_records);
	}
	
	public function print_page_nav($url , $cat = 'default', $pagevars = '')
	{
		if(empty($pagevars)) $pagevars = $this ->my_pages_vars;
		if(empty($pagevars)) return;
		//print_r($pagevars);
		$nav = (Object)$pagevars;

		$router = $this ->load('router');
		
		if($cat == 'default')
		{
			if ($nav -> total_pages == 1) return;

			$pagenav = '<ul class="pagination pull-right">';
			if ($nav -> page == 1)
			{
				$pagenav .= '<li><a href="javascript:void(0)" style="background-color:#eee">Prev</a></li>';
			}
			else
			{
				$pagenav.= '<li><a href="'.$router -> app_page($url,$nav -> page - 1,true).'">Prev</a></li>';
			}
				
			
			if($nav -> total_pages < 7)
			{
				for ($i = 1; $i <= $nav -> total_pages; $i++)
				{
					if($i == $nav -> page)
					{
						$pagenav .= "<li><a href=\"javascript:void(0)\" style=\"background-color:#eee\">".$i."</a></li>";
						continue;
					}
					$pagenav .= "<li><a href='".$router -> app_page($url,$i,true)."'>".$i."</a><li>";
				}

			}
			else//for those cases the total number is bigger than 7 pages
			{
				if($nav -> page < 5) //if current page is less than 5
				{
					for ($i = 1; $i <= 7; $i++)
					{
						if($i == $nav -> page)
						{
							$pagenav .= "<li><a href=href=\"javascript:void(0)\" style=\"background-color:#eee\">".$i."</a></li>";
							continue;
						}
						$pagenav .= "<li><a href='".$router -> app_page($url,$i,true)."'>".$i."</a><li>";
					}
					
				}
				else //else the beginning should not be 1 but do the calculation instead
				{
						//$this->finalPageArea .= "<label class='ppp'>...</label>";

						$finalPage = (($nav -> page + 3) < $nav -> total_pages) ? ($nav -> page + 3) : $nav -> total_pages;
						$firstPage = ($finalPage == $nav -> total_pages) ? $nav -> total_pages - 6 :  $nav -> page - 3;
							
						for ($i = $firstPage; $i <= $finalPage; $i++)
						{
								if($i == $nav -> page)
								{
									$pagenav .= "<li><a href=\"javascript:void(0)\" style=\"background-color:#eee\">".$i."</a></li>";
									continue;
								}
								$pagenav .= "<li><a href='".$router -> app_page($url,$i,true)."'>".$i."</a><li>";
						}
			
								//if($finalPage != $this -> totalPageNum)
								//	$this->finalPageArea .= "<label class='ppp'>...</label>";
				}
							
			}
			if($nav ->page < $nav ->total_pages) $pagenav .= '<li><a href="'.$router -> app_page($url,($nav -> page + 1),true).'">Next</a></li>';
			else $pagenav .= '<li><a href="javascript:void(0)" style="background-color:#eee">Next</a></li>';
			$pagenav .= '</ul>';
		}
		elseif($cat == 'faltty')
		{
			if ($nav -> total_pages == 1) return;

			$pagenav = '<ul>';
			if ($nav -> page == 1)
			{
				$pagenav .= '<li class="prev disabled"><a href="javascript:void(0)">← Previous</a></li>';
			}
			else
			{
				$pagenav.= '<li class="prev"><a href="'.$router -> app_page($url,$nav -> page - 1).'">← Previous</a></li>';
			}
				
			
			if($nav -> total_pages < 7)
			{
				for ($i = 1; $i <= $nav -> total_pages; $i++)
				{
					if($i == $nav -> page)
					{
						$pagenav .= "<li class=\"active\"><a href=\"javascript:void(0)\">".$i."</a></li>";
						continue;
					}
					$pagenav .= "<li><a href='".$router -> app_page($url,$i)."'>".$i."</a><li>";
				}

			}
			else//for those cases the total number is bigger than 7 pages
			{
				if($nav -> page < 5) //if current page is less than 5
				{
					for ($i = 1; $i <= 7; $i++)
					{
						if($i == $nav -> page)
						{
							$pagenav .= "<li class=\"active\"><a href=href=\"javascript:void(0)\">".$i."</a></li>";
							continue;
						}
						$pagenav .= "<li><a href='".$router -> app_page($url,$i)."'>".$i."</a><li>";
					}
					
				}
				else //else the beginning should not be 1 but do the calculation instead
				{
						//$this->finalPageArea .= "<label class='ppp'>...</label>";

						$finalPage = (($nav -> page + 3) < $nav -> total_pages) ? ($nav -> page + 3) : $nav -> total_pages;
						$firstPage = ($finalPage == $nav -> total_pages) ? $nav -> total_pages - 6 :  $nav -> page - 3;
							
						for ($i = $firstPage; $i <= $finalPage; $i++)
						{
								if($i == $nav -> page)
								{
									$pagenav .= "<li class=\"active\"><a href=\"javascript:void(0)\">".$i."</a></li>";
									continue;
								}
								$pagenav .= "<li><a href='".$router -> app_page($url,$i)."'>".$i."</a><li>";
						}
			
								//if($finalPage != $this -> totalPageNum)
								//	$this->finalPageArea .= "<label class='ppp'>...</label>";
				}
							
			}
			if($nav ->page < $nav ->total_pages) $pagenav .= '<li class="next"><a href="'.$router -> app_page($url,($nav -> page + 1)).'">Next → </a></li>';
			else $pagenav .= '<li><a href="javascript:void(0)">Next → </a></li>';
			$pagenav .= '</ul>';
		}
		echo $pagenav;
	}
	
	
	public function get_table_headers()
	{
		return $this -> my_table_headers;
	}
	
	public function get_helper_toolbar()
	{
		return $this -> my_helper_toolbar;
	}
	
	/**
	 * This function could be extended to config the table header , etc
	 * 
	 */
	public function config_table_headers()
	{
		
	}
	
	/**
	 * This function could be extended to config the table datas body , etc
	 *
	 */
	public function config_table_body()
	{
		$this -> config_general_info();
	}
	
	public function config_helper_toolbar()
	{
		
	}
	
	public function config_general_info()
	{
		
	}
	
	public function set_page_vars($pagevars)
	{
	    $this -> my_pages_vars = $pagevars;
	}
	
}




?>