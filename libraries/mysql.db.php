<?php
if (defined("CZCool") or die( "Access Denied!" ));
/* *******************************************
 * Copied Right Reserved (c) Compass Technology *
 * PDO Collection for db related operations     *
 * Author: Cavin Zhang @ Feb 25,2013            *
* ********************************************/

class Mydb {
	var $dbLink; 
	var $queryId;
	var $dbHostName;
	var $dbUsrName;
	var $dbPsWd;
	var $dbName;
	var $queryResult;
	var $rsRows;  // return the number of the query result
	
	function Mydb($hostName='', $usrName='', $psWord='',$dbName='')
	{	
		$this -> dbHostName = empty($hostName)? DB_HOSTNAME:$hostName ;
		$this -> dbUsrName = empty($usrName) ? DB_USERNAME: $usrName;
		$this -> dbPsWd = empty($psWord) ? DB_PASSWORD:$psWord ;
		$this -> dbName =  empty($dbName) ? DB_INSTANCE_NAME : $dbName ;
		$this -> dbLink = mysql_connect($this -> dbHostName, $this -> dbUsrName, $this -> dbPsWd);
		if(!$this->dbLink)
		{
			Logg::write('Can not connect to the DB server!!','SQL_ERR');
			$router = Console::getInstance('router');
			//loc_url($router->return_url('hey','err500'));
		}
		mysql_select_db($this -> dbName);
		mysql_query("SET NAMES 'utf8'",$this -> dbLink);
	}
	
	/*
	 * execute basical Query 
	 */
	function executeBasicQuery($sql)
	{
		
		if($this -> dbLink == "")
		{
			$this -> Mydb($this->dbHostName, $this->dbUsrName, $this->dbPsWd, $this->dbName);
		}
		try 
		{
			$this -> queryResult = mysql_query($sql);
			//check if there is any error occurs
			if(mysql_errno()) // return the error msg numberic msg from the previous operation
			{
				Logg::write(mysql_error(),'SQL_ERR');
				Logg::write($sql,'SQL_ERR');
				$router = Console::getInstance('router');
				//loc_url($router->return_url('hey','err500'));
			}
		}
		catch (Exception $e)
		{
			Logg::write($e->getMessage(),'SQL_ERR');
			$router = Console::getInstance('router');
			//loc_url($router->return_url('hey','err500'));
		}
		
		//if($isDevelopmentMode == 'ON')
		//{
		///	Logg::write($sql);
		//}
		
		return $this -> queryResult;
	}
	
	/*
	 * just get only one row of the queried results 
	 * return an array
	 */
	function getOneQuery($sql)
	{
		$row = '';
		$this -> executeBasicQuery($sql);
		if($this -> getQueryNum($sql) > 0)
		{
			$row = mysql_fetch_array($this -> queryResult);
			return $row;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	 * return an array after send the query
	 */
	function executeQuery($sql , $type = MYSQL_BOTH )
	{
		//$this -> executeBasicQuery($sql);
		if($this -> getQueryNum($sql) > 0)
		{
			while($row = mysql_fetch_array($this -> queryResult , $type))
			{
				$arr[] = $row;
			}
			return $arr;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	 * get total nubmer of the querying result
	 */
	function getQueryNum($sql)
	{

		$this -> queryResult = $this -> executeBasicQuery($sql);

		$this -> rsRows = @mysql_num_rows($this -> queryResult);
		return $this -> rsRows;
	}
	
	/* Phrase of SQL "Insert" function */
	function executeInsert($arr, $tbName)
	{
		
		$sqlInsert = 'INSERT INTO `'.$tbName.'`(';
		$tbColumn = '';
		$tbValMapping = '';
		foreach ($arr as $key => $val)
		{
			$tbColumn .= '`'.$key.'`,';
			$tbValMapping .= "'".mysql_real_escape_string($val)."',";			
		}
		$tbColumn = substr($tbColumn, 0, strlen($tbColumn)-1);
		$tbValMapping = substr($tbValMapping, 0, strlen($tbValMapping)-1);
		/* combine the SQL */
		$sqlInsert .= $tbColumn.') VALUES('. $tbValMapping. ')';
		//Logg::write($sqlInsert);
		//return the latest id
		$rs = $this -> executeBasicQuery($sqlInsert);//Logg::write($sqlInsert);
		if(!$rs){
			//Logg::write($sqlInsert);
			//$this -> msgErr($sqlInsert);
			return false;
		}
		//echo $sqlInsert;
		//echo mysql_insert_id();
		return mysql_insert_id();
	}
	
	/* Phrase of SQL "Update" function */
	function executeUpdate($arr, $tbName, $whereOrderBy)
	{
		$sqlUpdate = 'UPDATE `'. $tbName. '` SET';
		foreach ($arr as $key => $val)
		{
			$sqlUpdate .= "`". $key ."` = '". mysql_real_escape_string($val) ."',";
		}
		$sqlUpdate = substr($sqlUpdate, 0, strlen($sqlUpdate)-1);
		$sqlUpdate .= ' WHERE '. $whereOrderBy;
		//echo $sqlUpdate;
		return $this -> executeBasicQuery($sqlUpdate);
	}

	
	/* Phrase of SQL "Delete" function
	 * Params: 2D array, array('table_name'=>array('column_name'=>'***'[,'column2'=>'****']))
	 * Support several tables' deletion at the same time
	 * */
	function executeDeletion($arr)
	{
		if(is_array($arr))
		{
			foreach ($arr as $table => $val)
			{
				$sql_del = "DELETE FROM `".$table."` WHERE ";
				$numOfCol = count($val);
				if($numOfCol <=1)
				{
					foreach ($val as $key => $val2)
					{
						$sql_del .= " `".$key."` = '".mysql_real_escape_string($val2)."'";
					}
				}
				else
				{
					$k = 0;
					foreach ($val as $key => $val2)
					{
						$k++;
						$sql_del .= " `".$key."` = '".mysql_real_escape_string($val2)."'";
						if($k < $numOfCol)
						{
							$sql_del .= " AND ";
						}
					}
				}
				$rs = $this -> executeBasicQuery($sql_del);
			
			} 
		}
		//Logg::write($sql_del);
		return $rs;
	}
}

?>