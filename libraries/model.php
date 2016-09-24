<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * @Description: DB OperCollection
 */
class Model {
	/*
	 * Table name
	 * Primary Key
	 * Current OperObjID - for update/delete purpose
	 * */
	protected $tb;
	protected $tbAlias;
	protected $pk;
	protected $id;
	protected $model;
	protected $sqlQuery;
	protected $totalRecords;
	protected $totalPages;
	protected $query_vars;
	protected $page_vars;
	/**
	 */
	function __construct() {
		$this -> model = Console::getInstance('mydb');
	}
	

	public function setTable($tb, $pk='id', $tbAlias = 'a')
	{
		
		$this ->tb = (strpos($tb, DBPRE) === false) ? DBPRE.$tb : $tb;
		$this ->pk = $pk;
		$this ->tbAlias = $tbAlias;
		return $this;
	}
	
	public function getTable()
	{
		return $this -> tb;
	}
	
	public function select($stringParams)
	{
		$this -> sqlQuery['select'] .= $stringParams;
		return $this;
	}
	
	public function where($arrParams)
	{
		$this -> sqlQuery['where'] = $arrParams;
		return $this;
	}
	
	public function setUpdate($arrParams)
	{
		$this -> sqlQuery['update'] = $arrParams;
		return $this;
	}
	
	public function update($sql='')
	{
		if($sql == '')
		{
			$sql = $this ->getSqlStatement();
		}
		
		$result = $this -> model -> executeBasicQuery($sql);
		
		return $result;
	}
	
	public function query($sql='' , $type = MYSQL_ASSOC)
	{
		if($sql == '')
		{
			$sql = $this ->getSqlStatement();
		}
		
		//Logg::write($sql);
		$this ->query_vars = $this -> model -> executeQuery($sql , $type);
		
		return $this ->query_vars;
	}
	
	public function insert($arr,$tb = '')
	{
		return $this -> model -> executeInsert($arr, ($tb == '')?$this->tb:DBPRE.$tb);
	}
	
	public function delete($sql='')
	{
		if($sql == '')
		{
			$this->sqlQuery['delete'] = true;
			$sql = $this ->getSqlStatement();
		}
		
		$result = $this -> model -> executeBasicQuery($sql);
		
		return $result;
	}
	
	public function getOne($sql='')
	{
		if($sql == '')
		{
			$sql = $this ->getSqlStatement();
		}
		
		//Logg::write($sql);
		$this ->query_vars = $this -> model -> getOneQuery($sql);
		
		return $this ->query_vars;
	}
	
	public function order($orderBy, $asc = 'DESC')
	{
		$this -> sqlQuery['order'] = $orderBy;
		$this -> sqlQuery['asc'] = $asc;
		return $this;
	}
	
	public function l_join($tb, $alias, Array $array)
	{
		if(!isset($this ->sqlQuery['join'])){
			$this ->sqlQuery['join'] = array();
		}
		if(is_array($array)){
			$onStr = '';
			foreach ($array as $key => $val){
				$onStr .= $key.' = '.$val.',';
			}
			$onStr = substr($onStr, 0,(strlen($onStr)-1)).' ';
		}
		array_push($this ->sqlQuery['join'], array('type'=>'LEFT','tb'=>$tb .' AS '.$alias, 'on'=>$onStr));
		return $this;
	}
	
	public function in_join($tb, $alias, Array $array)
	{
		if(!isset($this ->sqlQuery['join'])){
			$this ->sqlQuery['join'] = array();
		}
		if(is_array($array)){
			$onStr = '';
			foreach ($array as $key => $val){
				$onStr .= $key.' = '.$val.',';
			}
			$onStr = substr($onStr, 0,(strlen($onStr)-1)).' ';
		}
		array_push($this ->sqlQuery['join'], array('type'=>'INNER','tb'=>$tb .' AS '.$alias, 'on'=>$onStr));
		return $this;
	}
	
	public function getSqlStatement()
	{
		$isUpdate = false;
		$isDelete = false;
		if(isset($this ->sqlQuery['select']))
		{
			$sql = 'SELECT '.$this ->sqlQuery['select'].' ';
			$sql .= 'FROM `'.$this -> tb.'` AS '.$this->tbAlias;
			if(isset($this ->sqlQuery['join']))
			{
				foreach ($this ->sqlQuery['join'] as $key){
					$sql .= ' '.$key['type'].' JOIN '.(strpos($key['tb'], DBPRE) === false  ? DBPRE.$key['tb'] : $key['tb']).' ON '.$key['on'];
				}
			}
		}
		elseif(isset($this->sqlQuery['update']))
		{
			$isUpdate = true;
			$sql = 'UPDATE `'. $this -> tb. '` SET';
			foreach ($this->sqlQuery['update'] as $key => $val)
			{
				if(strpos($val,'`')!==false){
					$char = "";
				}else{
					$char = "'";
				}
				$sql .= "`". $key ."` = ".$char. mysql_real_escape_string($val) .$char.",";
			}
			$sql = substr($sql, 0, strlen($sql)-1);
		}
		elseif(isset($this->sqlQuery['delete']))
		{
			$isDelete = true;
			$sql = "DELETE FROM `".$this -> tb."`";
		}
		
		if(isset($this ->sqlQuery['where']))
		{
			$sql .= ' WHERE 1=1 ';
			if(is_array($this-> sqlQuery['where']))
			{
				foreach ($this->sqlQuery['where']  as $key => $val)
				{
					if (strpos($key, "#")){
						$key = explode('#', $key);
						switch($key[1]){
							case 'IN':
								$sql .= ' AND '.$key[0].' '.$key[1]. '('.$val.')';
								break;
							case 'NOT IN':
								$sql .= ' AND '.$key[0].' '.$key[1]. '('.$val.')';
								break;
							case 'LIKE':
								$sql .= ' AND '.$key[0].' '.$key[1]. '"'.$val.'"';
								break;
							case 'OR':
								$sql .= ' OR '.$key[0].' = "'.$val.'"';
								break;
							case 'BG':
							    $sql .= ' AND '.$key[0].' > "'.$val.'"';
							    break;
							case 'LT':
							    $sql .= ' AND '.$key[0].' < "'.$val.'"';
							    break;
							case 'BGEQ':
							    $sql .= ' AND '.$key[0].' >= "'.$val.'"';
							    break;
							case 'LTEQ':
							    $sql .= ' AND '.$key[0].' <= "'.$val.'"';
							    break;
						}
						
					}
					else
						$sql .= ' AND '.$key.' = "'.$val.'"';
				}
			}
			else
			{
				$sql .= $this->sqlQuery['where'];
			}
		}
		
		if($isUpdate === false && $isDelete === false)
		{
			if(isset($this ->sqlQuery['order']))
				$sql .= ' ORDER BY '.$this -> sqlQuery['order'].' '.$this -> sqlQuery['asc'];
			/*
			else
				$sql .= ' ORDER BY '.$this->pk.' DESC';
			*/
			if(isset($this ->sqlQuery['limit']))
				$sql .= $this ->sqlQuery['limit'];
		}

		//logg::debug($sql);
		return $sql;
	}
	
	public function cleanUp($key='')
	{
		if(isset($key) and $key !=''){
			unset($this -> sqlQuery[$key]);
		}
		else 
			$this -> sqlQuery = array();
		return $this;
	}
	
	public function limit($page='', $recordPerPage='')
	{
		
		$zPage = ((! isset ($page)) or ($page <= 0)) ? 1 : $page;
		if(empty($recordPerPage)) {
		    if(defined('AdmBasePath')) $recordPerPage = CMemory::get('admin_config.admin_records');
		} 
	    if(!$recordPerPage)
		  $recordPerPage = C('records_per_page');
		
		$this ->totalRecords = $this ->model->getQueryNum($this->getSqlStatement());
		
		if($page == 0){  
			//if return as zero , then return all
			unset($this ->sqlQuery['limit']); 
			$this ->totalPages = 1;
			return $this;
		} 
 		
		$this ->totalPages = ceil ( $this ->totalRecords / $recordPerPage );
		$offset = ($zPage - 1) * $recordPerPage;
	
		$this -> page_vars = array(
			'page'					=>		$zPage								,
			'total_pages'		=> 		$this ->totalPages			,
			'nums'					=>		$this ->totalRecords
		);
		
		$this ->sqlQuery['limit'] = ' LIMIT '. $offset.','.$recordPerPage;
	
		return $this;
	}
	
	public function formatTable($tb)
	{
		return strpos($tb, DBPRE)==false?DBPRE.$tb:$tb;
	}
	
	public function get_pages_vars()
	{
		return $this -> page_vars;
	}
	
	/**
	 * @tutorial: This is the common model to check if the record exist
	 * 
	 * @param unknown $key
	 * @return all infomation in the table
	 */
	public function check_record_exists($key, $where = array())
	{
		$pk_cond = array();
		
		if(!empty($key)) $pk_cond = array($this->pk=>$key);
		
		$where = ($where)?array_merge($where,$pk_cond):$pk_cond;
		
		if(!$where) return false;
		
		return  $this -> select('*')->where($where)->getOne();
	}
	
	/**
	 * @tutorial: This is the common model to fetch all the records by passed the page num
	 * 
	 * @param unknown $page
	 * @param return all records with all info
	 */
	public function get_record_list($page , Array $params = array())
	{
		$page = (isset($page) && $page > 0 )?$page:1;
		
		return  $this -> select('*')-> where($params) -> limit($page)->order($this->pk)->query();
	}
	
	/**
	 * @tutorial: This is the common model to remove the required record by passed the primary key
	 * 
	 * @param unknown $key
	 * @return boolean
	 */
	public function remove_record($key)
	{
		if(empty($key)) return false;
		
		$sql = 'DELETE FROM `'.$this->tb.'` WHERE `'.$this->pk.'` = "'.mysql_real_escape_string($key).'"';
		
		return $this->delete($sql);
	}
	
	/**
	 * @tutorial: This is the common model to update an existing records
	 * 
	 * @param unknown $key
	 * @param unknown $updArray
	 * @return boolean
	 */
	public function update_record($key, $updArray)
	{
		if(empty($key)) return false;
		
		if(!is_array($updArray)) return false;
		
		return $this ->setUpdate($updArray)->where(array($this->pk=>mysql_real_escape_string($key)))->update();
	}
	
	public function from($tb , $pk='id', $tbAlias = 'a')
	{
		$this -> setTable($tb, $pk, $tbAlias);
		return $this;
	}
	
	public function get_memcached_query($getmutiple =  true, $prefix = '',$calledby = '')
	{
	    $sql = $this -> getSqlStatement();
	    
	    $sqlk = $prefix.'_'.md5($sql);
	    
	    if(!$rs = CMemory::mc_get($sqlk)){
	    
	        logg::debug($calledby.' NOT exist key ->'.$sqlk);
	    
	        $rs = ($getmutiple) ? $this -> query() : $this -> getOne();
	    
	        CMemory::mc_set($sqlk, $rs);
	    }
	    else
	        logg::debug($calledby.' Key found ->'.$sqlk);
	    
	    return $rs;
	}
}

?>