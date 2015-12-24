<?php
/**
 * Mysqli数据库操作基类
 * 
 * @author 449127727@qq.com
 */
class DB_Mysqli
{

	//调式模式
	public static $DEBUG = 0;
	private static $links = array();
	private $dbhost;
	private $dbuser;
	private $dbpass;
	private $dbname;
	private $dbport;
	private $dbconn;

	/**
	 * 初始化连接参数
	 */
	public function __construct($dbhost, $dbuser, $dbpass, $dbname, $dbport=3306) 
	{
		$this->dbhost = $dbhost;
		$this->dbuser = $dbuser;
		$this->dbpass = $dbpass;
		$this->dbname = $dbname;
		$this->dbport = $dbport;
	}

	
	/**
	 * 获取dbname的全局唯一的数据库实例（单例模式）dbname做区分
	 * @param string $dbhost
	 * @param string $dbuser
	 * @param string $dbpass
	 * @param string $dbname
	 * @return Mysqli
	 */
	public static function getInstance($dbhost, $dbuser, $dbpass, $dbname,$dbport=3306) 
	{
		$key = md5($dbhost . $dbuser . $dbpass . $dbname . $dbport);
		if (empty(self::$links[$key])) 
		{
			self::$links[$key] = new self($dbhost, $dbuser, $dbpass, $dbname, $dbport);
		}

		return self::$links[$key];
	}
	
	/**
	 * 连接数据库 返回唯一的数据库连接
	 * 
	 * @return mysqli
	 */
	public function connect() 
	{ 
		//长时间未使用导致超时，进行重连
		if (!empty($this->dbconn) && !$this->dbconn->ping()) 
		{
			$this->dbconn->close();
			$this->dbconn = null;
		}

		if ($this->dbconn == null) 
		{
			$this->dbconn = new mysqli ($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname, $this->dbport);
			if (mysqli_connect_errno()) 
			{
				$this->halt();
			} else {
				$this->query("set names utf8");
			}
		}

		return $this->dbconn;
	}

	

	/**
	 * 执行SQL
	 * 
	 * @param $string $sql        	
	 * @return mysqli_result
	 */
	public function query($sql) 
	{
		$this->connect();
		$rs = $this->dbconn->query($sql);
        $this->halt($sql);
		return $rs;
	}
	
	/**
	 * 获取影响的行数
	 * @return int
	 */
	public function affect_rows()
	{
	    return $this->dbconn->affected_rows;
	}
	
	/**
	 * 关闭数据库
	 */
	public function close()
	{
		$this->dbconn->close();
	}
	
	
	/**
	 * 通过一个SQL语句获取一行信息(字段型)
	 *
	 * @access public
	 * @param string $sql SQL语句内容
	 * @return mixed
	 */
	public function fetchRow($sql, $type = MYSQLI_ASSOC) 
	{
		$result = $this->query($sql);
        if ($result) {
            $row = $result->fetch_array($type);
            $result->free();
            return $row;
        }
        return false;
	}
	
	/**
	 * 获取当前行数据的key字段的value值
	 * @param string $sql
	 * @param string $column
	 * @return mixed
	 */
	public function fetchValue($sql, $column) 
	{
		$row = $this->fetchRow($sql);
		return isset($row[$column]) ? $row[$column] : false;
	}
	
	
	/**
	 * 获取所有数据行
	 * @param string $sql
	 * @param string $type
	 * @return array
	 */
	public function fetchAll($sql, $type = MYSQLI_ASSOC) 
	{
		$result = $this->query($sql);
		$rows = array();
		if ($result) { 
			while ($row = $result->fetch_array($type)) {
				$rows[] = $row;
			}
			$result->free ();
		}
		return $rows;
	}

	/**
	 * 通过一个SQL语句获取全部信息(字段型)
	 *
	 * @access public
	 * @param string $sql SQL语句
	 * @param string $key 一般为id字段
	 * @return array
	 */
	public function getIndexArray($sql, $indexColumn = '') 
	{
		$rows = $this->fetchAll($sql);
		
		if(!$key) {
		    return $rows;
		}
		
		$array = array();

		foreach ($rows as $row) {
		    $array[$row[$indexColumn]] = $row;
		}

		return $array;		
	}
	
	/**
	 * 插入一行数据到表格，并返回主键ID
	 * @param string $table
	 * @param array $data
	 * @return number 失败返回false
	 */
	public function insert($table, $data) 
	{
		if(!is_array($data) || empty($data)) {
			return 0;
		}

		$this->connect();
		$columns = $values = array();		
		foreach ( $data as $k => $v ) {
			$k = $this->dbconn->real_escape_string($k);
			$v = $this->dbconn->real_escape_string($v);
			array_push($columns, "`$k`");
			array_push($values, "'$v'");
		}

		$columns = implode(',', $columns);
		$values = implode(',', $values);
		$sql = "insert into {$table} ({$columns}) values ({$values})";

		if ($this->query($sql)) {
			return $this->insert_id();
		}

		return false;
	}

	/**
	 * replaceInto 请注意mysql中使用replace into的限制
	 * @param      string $table
	 * @param      array $data
	 * @access     public
	 * @return     boolean|int
	 * @update     2013-8-19 18:05:58
	 */
	public function replace($table, $data)
	{
		$columns = "";
		$values = "";
		$data['update_time'] = time();
		foreach( $data as $k=>$v )
		{
			$columns .= "`{$k}`,";
			$values .= "'" . self::escape( $v ) . "',";
		}
	
		$columns = trim( $columns, ',' );
		$values = trim( $values, ',' );
		$sql = "replace into {$table}( {$columns} ) values ( {$values} )";
		$rs = $this->dbconn->query( $sql );
	
		if ( false !== $rs ) {
			$id = $this->dbconn->insert_id();
			if ( $id ) {
				return $id;
			}
		}
		return $rs;
		 
	} // end func

	/**
	 * 根据id逻辑删除一条记录
	 * @param      int $id
	 * @access     public
	 * @return     boolean
	 * @update     2013/6/14 11:14:34
	*/
	public function delete($table, $id)
	{
		$sql = "delete from {$table} where id=" . (int)$id;
		return $this->query($sql);
	} // end func

	/**
	* 批量删除
	* @param      string $where_clause
	* @access     public
	* @return     boolean
	* @update     2013/6/19
	*/
	public function batchDelete($table, $where_clause) 
	{
		$sql = "delete from {$table} {$where_clause}";
		return $this->query( $sql ); 
	} // end func



	/**
	 * 批量修改
	 * @param  string $table
	 * @param  array $data
	 * @param array $condition
	 * @return boolean 
	 * @added by jiangwb 2013-07-09
	 */
	public function batchUpdate($table, $data, $where = "where 1=2")
	{
		if(!is_array($data) || empty($data)) {
			return 0;
		}

		$set = '';
		foreach($data as $k=>$v){
			$set .= "`$k`"."='" . self::escape($v) . "',";
		}
		$updatastr = 'set ' . rtrim($set,',');
		$sql = "update {$table} {$set} {$where}";
		return $this->query($sql);
	}
	
	/**
	 * last insert id
	 * @param      none
	 * @access     public
	 * @return     void
	 * @update     2013/6/13 16:17:03
	*/
	function insert_id()
	{
		return $this->dbconn->insert_id;	    
	} // end func



	/**
	 * 中断控制
	 * @param      string $msg
	 * @access     private
	 * @return     void
	 * @update     2013/6/14 11:45:33
	*/
	private function halt( $sql = 'NO SQL' )
	{
		if(self::$DEBUG)
		{
			printf( "mysqli>>> mysql_errno:%s\t mysql_error:%s\t <font color='red'>SQL:%s</font>\r\n<br/>", $this->dbconn->errno, $this->dbconn->error, $sql );
		}

        Common_Log::mysql(sprintf("mysql_errno:%s\t mysql_error:%s\t SQL:%s", $this->dbconn->errno, $this->dbconn->error, $sql));

		//这里写日志

	} // end func

	/**
	 * 转义特殊字符
	 * @param string $str
	 * @return string
	 */
	public static function escape( $str ) 
	{ 
	    return empty( $str ) ? $str : mysql_escape_string( strval( $str ) );
	}

	/**
	 * 对查询条件值过滤
	 * @param string $str
	 * @return string
	 */
	public static function filter( $str ) 
	{	    
	    if(empty($str)) {
	        return $str;
	    }

	    $str = str_replace( '%', '\%', $str );
	    $str = str_replace( '_', '\_', $str );	    
	    $str = mysql_escape_string($str);	  
	    return $str;	    
	}
}
