<?php
class DBTool_DB {
	public function getConnt(){
		
		require(APP_PATH . "/conf/db.config.php");
		
		$msqli = new MYSQLi($db['host'],$db['user'],$db['password'],$db['database']);
		$msqli->query("set names utf8");
		//$msqli = new mysqli('localhost','root','123456', 'tcsys');
		if($msqli->connect_error){
			die("连接失败".$msqli->connect_error);
		}
		return $msqli;
	}
	
	//查询
	public  function query($sql,$params){
		$msqli = $this->getConnt();
		$mysqli_stmt = $msqli->prepare($sql);
		if($params != null){
			call_user_func_array(array($mysqli_stmt, 'bind_param'), $this->_refValues($params));   //绑定参数
		}
		
	    $mysqli_stmt->execute();
		$meta = $mysqli_stmt->result_metadata();
        //将结果绑定数组元素设置为引用状态,因为call_user_func_array(array($stmt, 'bind_result'), $parameters)中的回调函数参数$parameters需要引用状态.
        $row = array();
        while ($field = $meta->fetch_field()) {
            $parameters[] = &$row[$field->name];
        }
        call_user_func_array(array($mysqli_stmt, 'bind_result'), $this->_refValues($parameters));  //绑定结果
        $results = array();
        //有多行记录时将多行记录存入$results数组中.
        while ($mysqli_stmt->fetch()) {
            $x = array();
            foreach ($row as $key => $val) {
                $x[$key] = $val;
            }
            $results[] = $x;
        }
	   $mysqli_stmt->close();
	   $msqli->close();
       return $results;
	}
	
	//删除，更新，插入用
	public function del_up_inert_data($sql,$params){
		$msqli = $this->getConnt();
		$mysqli_stmt = $msqli->prepare($sql);
		
		if($params != null){
			call_user_func_array(array($mysqli_stmt, 'bind_param'), $this->_refValues($params));   //绑定参数
		}
		
		$flag = $mysqli_stmt->execute();
		$mysqli_stmt->close();
		$msqli->close();
		return $flag;
	}
	
	
	/*
	 * 作用:把返回的数组中的元素变为引用状态.
	* (如果$arr为含有引用状态元素的数组,则会影响调用者的参数数组,反之则反)
	*/
	
	function _refValues($arr) {
		if (strnatcmp(phpversion(), '5.3') >= 0) { //Reference is required for PHP 5.3+
			$refs = array();
			foreach ($arr as $key => $value) {
				$refs[$key] = &$arr[$key];
			}
			return $refs;
		}
		return $arr;
	}
}