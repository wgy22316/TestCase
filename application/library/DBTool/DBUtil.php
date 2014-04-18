<?php
	class DBTool_DBUtil{
// 		private $url="localhost";
// 		private $user="root";
// 		private $password="root";
// 		private $database="library";
		private $conn;  //连接
		private $res; //结果集
		
		public function __construct(){
			require(APP_PATH . "/conf/db.config.php");
			 
			$this->conn = new MYSQLi($db['host'],$db['user'],$db['password'],$db['database']);
// 			$this->conn=new MySQLi($this->url,$this->user,$this->password,$this->database);
			if($this->conn->connect_error){
				echo $this->conn->connect_error;
			}
			$this->conn->query("set names utf8");
		}
		//dml语句 : insert,update,delete
		/**
		 * 执行数据操作语句
		 * @param $sql insert/update/delete语句
		 * @return 1:成功<br> 2:出错 <br> 3：没有行受到影响
		 */
		public function executeDML($sql){
			if($this->conn->query($sql))
				return 1;
			else
				return 2;
			// $affectedRows=$this->conn->affected_rows;
			// if($affectedRows>0){
			// 	return 1;//success
			// }else if($affectedRows==0){
			// 	return 3;//没有行受影响	
			// }else{
			// 	return 2;//出错
			// }

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

		/**
		 * 执行数据操作语句
		 * @param $sql insert/update/delete语句
		 * @return 1:成功<br> 2:出错 <br> 3：没有行受到影响
		 */
		public function executeSTMT($sql,$params){
			//			$this->conn->query($sql,$data);
			$stmt = $this->conn->prepare($sql);
			// $stmt->bind_param($type,$data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6]);
			call_user_func_array(array($stmt,"bind_param"), $this->_refValues($params));
			mysqli_stmt_execute($stmt);
			// 			echo "test";
			$affectedRows=$this->conn->affected_rows;
			if($affectedRows==1){
				return 1;//success
			}elseif($affectedRows==0){
				return 3;//没有行受影响
			}else{
				return 2;//出错
			}
		}

		/**
		*执行数据插入操作并返回id
		*@param $sql insert语句
		*@param $params 要绑定的参数
		*@return id
		*/
		public function executeInsert($sql,$params){
			$stmt = $this->conn->prepare($sql);
			call_user_func_array(array($stmt,"bind_param"), $this->_refValues($params));
			mysqli_stmt_execute($stmt);
			$affectedRows=$this->conn->affected_rows;
			if($affectedRows==1){
				return $this->conn->insert_id;//success
			}elseif($affectedRows==0){
				return -2;//没有行受影响	
			}else{
				return -3;//出错
			}
		}


		
		
		//dql语句：select 
	/**
		 * 执行数据查询语句，返回数组
		 * @param $sql 查询SQL
		 * @param $type  查询方式，MYSQLI_NUM/MYSQLI_ASSOC/MYSQLI_BOTH
		 * @return 返回关联数组
		 */
		public function executeDQL($sql,$type){
			$arr=array();
			$res=$this->conn->query($sql);
			while(($row=$res->fetch_array($type))!=false){
				//这种写法，数组会自动添加内容
				$arr[]=$row;
			}
			//第二种写法：把结果集复制到数组中,Java是把结果集放到arrayList中		
			/*
			$rowsnum= $res->num_rows;
			for($j=0;$j<$rowsnum;$j++){
				$arr[$j]=$res->fetch_array(MYSQLI_ASSOC);
			}
			*/
			//第三种写法：
			/*
			$i=0;
			while($row=$res->fetch_array(MYSQLI_ASSOC)){
				$arr[$i++]=$row;
			}
			*/
			$res->free();
			return $arr;
		}
		
		/**
		 *
		 * @param array $arr  sql数组
		 * @return boolean  success true
		 */
		public function executeMutilSQL($arr){
			$this->conn->query("SET AUTOCOMMIT=0");
			$flag=true;
			foreach($arr as $v){
				file_put_contents("SQL.log", $v."\r\n\n",FILE_APPEND);
				$res=	$this->conn->query($v);
				if($res==false){
					$flag=false;
					break;
				}
			}
			if($flag){
				$this->conn->query("COMMIT");
				$this->conn->query("END");
				$this->conn->query("SET AUTOCOMMIT=1");
				return true;
			}else{
				$this->conn->query("ROLLBACK");
				$this->conn->query("END");
				$this->conn->query("SET AUTOCOMMIT=1");
				return false;
			}
		}
		
		public function close_conn(){
			if(!empty($this->conn)){
				$this->conn->close();
			}
		}
	}
?>