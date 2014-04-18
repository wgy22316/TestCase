<?php

class DBTool_ConnectDB
{

  /** 
    *  静态成员变量 保存全局实例 
    *  @access private 
    */
	  public $conn;
    public static $sql;
    public static $instance=null;
    static private $_instance = NULL;  
  /** 
    * 私有化构造函数，防止外界实例化对象 
        */ 
    private function  __construct() {
    	require_once(APP_PATH . "/conf/db.config.php");
    	
    	$this->conn = new mysqli($db['host'],$db['user'],$db['password'],$db['database']);
    	
    	if(mysqli_connect_errno()){
    		throw new Exception("connect mysql error:". mysql_error());
    			exit ( );
		  } else {
// 			printf("Connect succeeded/n");
		  } 
    	$this->conn->query("set names utf8");
    } 
    /** 
     *  私有化克隆函数，防止外界克隆对象 
     */ 
    private function  __clone(){
    	throw new Exception("Singleton Class Can Not Be Cloned");
    } 
    /** 
     *  静态方法, 单例统一访问入口 
     *  @return  object  返回对象的唯一实例 
     */ 
    static public function getInstance() { 
        if (is_null(self::$_instance) || !isset(self::$_instance)) { 
             self::$_instance = new DBTool_ConnectDB(); 
        } 
        return self::$_instance; 
    }   
    /** 
     * 执行sql
     */ 
//     public function queryOne($sql) {
//     	$result = mysql_query($sql);
//     	while($row = mysql_fetch_array($result))
//     	{
//     		echo $row['FirstName'] . " " . $row['LastName'];
//     		echo "<br />";
//     	}
    	
//     } 

//     public function 
    
    public function close() {
    	mysql_close($this->conn());
    } 
} 


