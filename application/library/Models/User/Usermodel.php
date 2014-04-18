<?php

// namespace user\UserModel;
// use user;
// use tool;
// include 'Models_User_IUser.php';
// include 'LoginModel.php';
// include 'UserInfo.php';

class Models_User_Usermodel implements Models_User_IUser
{
	
	/* (non-PHPdoc)
	 * @see \modules\user\IUser::isUser()
	 */
	private $_usertable="tc_userinfo";
	public function UserModel(){
		
	}
	public function isUser($user) {
		try{
			$instance = new DBTool_DBUtil();
			$sql = "select  user_id from ".$this->getUsertable()." where user_id='".$user->getUser_id()."' and password = '".$user->getPassword()."' limit 1";
			
			$stmt = $instance->executeDQL($sql,MYSQL_ASSOC);
			if($stmt){		
					$instance->close_conn();
					return true;
			}
			else{
				$instance->close_conn();
				return false;
			}
		}catch(Exception $e){
			$instance->close_conn();
			return false;
		}
			$instance->close_conn();
			return false;

	}

	/* (non-PHPdoc)
	 * @see \modules\user\IUser::addUser()
	 */
	
	public function addUser($user) {
		try{
			$instance = new DBTool_DBUtil();
			$sql = "insert into  ".$this->getUsertable()."(user_id,username,password,email) values(
				'".$user->getUser_id().
				"','".$user->getUsername().
				"','".$user->getPassword().
				"','".$user->getEmail()."');";
			$result = $instance->executeDML($sql);//执行sql语句 
			if($result==1){//更新权限表初始权限

				$privi_sql = "insert into tc_user_privilege(user_id,module_id,group_id) values(
							'".$user->getUser_id()."','0,1',1000)";
				$result2 = $instance->executeDML($privi_sql);//执行sql语句 
				if($result2==1){
					$instance->close_conn();
					return true;
				}else{
					$instance->close_conn();
					return false;
				}
			}
			else{
				$instance->close_conn();
				return false;
			}
		}catch(Exception $e){
			$instance->close_conn();
			return false;
		}
		$instance->close_conn();
		return false;
		
	}

	

	/* (non-PHPdoc)
	 * @see \modules\user\IUser::findUserById()
	 */
	public function findUserByKey($key,$value) {
		try{
			$instance = new DBTool_DBUtil();
			$sql = "select * from ".$this->getUsertable()." where ".$key."='".$value."' limit 1";
			$stmt = $instance->executeDQL($sql,MYSQL_ASSOC);
			if($stmt){		
				while(($row=$stmt->fetch_array(MYSQLI_ASSOC))!=false){
					//这种写法，数组会自动添加内容
					$array = $row;
	// 				$userinfo = new UserInfo($username ,$password,$name,$email,$tel,$address,$res_time,$remarks);
	// 				$userinfo = new UserInfo($row['username '],$row['password'],$row['name'],$row['email'],$row['tel'],$row['address'],$row['res_time'],$row['remarks']);
				}
				$instance->close_conn();
				return $array;
			}
			else{
				$instance->close_conn();
				return false;
			}
		}catch(Exception $e){
			$instance->close_conn();
			return false;
		}

		$instance->close_conn();
		return null;
	}

	/**
	 * zhoujx
	 * 判断数据库中是否存在key对应的value（前置条件：key[数据库对应字段名]、value[需要判断的值]）
	 * 如果存在 return true
	 * 其他情况 return false
	 *
	 * */
	public function isExistByKey($key,$value) {
		try{
			$instance = new DBTool_DBUtil();
			$sql = "select ".$key." from ".$this->getUsertable()." where ".$key."='".$value."' limit 1;";
			$result = $instance->executeDQL($sql,MYSQL_ASSOC);
			if($result) {
				$instance->close_conn();
				return true;
			}
			else{
				$instance->close_conn();
				return false;
			}
		}catch(Exception $e){
			$instance->close_conn();
			return false;
		}
		$instance->close_conn();
		return false;
		
	}
	/**
	 * zhoujx
	 * 判断数据库中是否存在key对应的value（前置条件：key[数据库对应字段名]、value[需要判断的值]）
	 * 如果存在 return true
	 * 其他情况 return false
	 *
	 * */
	public function updatePassword($newpass,$email){
		try{
			$instance = new DBTool_DBUtil();
			$sql = "update   ".$this->getUsertable()." set password='".$newpass."' where email='".$email."' ;";
			$result = $instance->executeDML($sql);
			if($result==1){
				$instance->close_conn();
				return true;
			}
			else{
				$instance->close_conn();
				return false;
			}
		}catch(Exception $e){
			$instance->close_conn();
			return false;
		}
		$instance->close_conn();
		return false;
	}

	/* (non-PHPdoc)
	 * @see \modules\user\IUser::updateUser()
	 */
	public function updateUser($user) {
		try{
			$instance = new DBTool_DBUtil();
			$sql = "update   ".$this->getUsertable()." set password='".$user->getPassword()."' where user_id='".$user->getUser_id()."' ;";
			$result = $instance->executeDML($sql);
			if($result==1){
			$instance->close_conn();
				return true;
			}
		}catch(Exception $e){
			$instance->close_conn();
			return false;
		}
		$instance->close_conn();
		return false;
	}
	/**
	 * @return the $_usertable
	 */
	public function getUsertable() {
		return $this->_usertable;
	}

	/**
	 * @param string $_usertable
	 */
	public function setUsertable($_usertable) {
		$this->_usertable = $_usertable;
	}

	
	
}

