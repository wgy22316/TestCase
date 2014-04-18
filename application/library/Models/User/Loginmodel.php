<?php

// namespace user\LoginModel;
// use user;
// use tool;
//include 'Models_User_ILogin.php';
 class Models_User_Loginmodel implements Models_User_ILogin
{
	
	/* (non-PHPdoc)
	 * @see \modules\user\ILogin::isLogin()
	 */
	public function Models_User_Loginmodel(){
		
	}
	private $_logintable="tc_userinfo";


	/**
	 * zhoujx
	 * 判断是否为本网站用户
	 * input:user object
	 * output:username  用户的名称 ,null 不是本站用户
	 */
	
	public function findUser($user) {
		try{
			$instance = new DBTool_DBUtil();
			$sql = "select username from ".$this->getLogintable()." where user_id='".$user->getUser_id()."' and password = '".$user->getPassword()."' limit 1";
			
			//file_put_contents("SQL.log", $group_menu."\r\n",FILE_APPEND);
			$user=$instance->executeDQL($sql,MYSQL_ASSOC);
			if($user==null){
				$instance->close_conn();
				return null;
			}
			else{
				$username = $user[0]['username'];
				$instance->close_conn();
				return $username;
			}
		}catch(Exception $e){
			$instance->close_conn();
			return null;
		}

		$instance->close_conn();
		return null;
	}
		

	/* (non-PHPdoc)
	 * @see \modules\user\ILogin::addLogin()
	 */
	
	// public function addLogin($username, $password) {
	// 	$instance = DBTool_ConnectDB::getInstance();
	// 	$sql = "insert into  ".$this->getLogintable()."(user_id,password) values(?,?)";
	// 	try{
	// 		$stmt=$instance->conn->prepare($sql);
	// 			$stmt->bind_param("ss",$username,$password);
	// 			$stmt->execute();//执行sql语句 
	// 			$id=$instance->conn->insert_id;//获取刚插入的ID号
	// 			if($id>0){
	// 				$stmt->close();
	// 				return true;
	// 			}
	// 			$stmt->close();
	// 			return false;
	// 	}catch(Exception $e){
	// 		$stmt->close();
	// 		return false;
	// 	}
		
	// }

	/* (non-PHPdoc)
	 * @see \modules\user\ILogin::updateLogin()
	 */
	// public function updateLogin($username, $password) {
	// 	$instance = DBTool_ConnectDB::getInstance();
	// 	$sql = "update  ".$this->getLogintable()." set password ='".$password."' where user_id ='".$username."' ;";
	// 	try{
	// 		$stmt=$instance->conn->prepare($sql);
	// 		$stmt->execute();
	// 		// if($result)
	// 		$stmt->close();
	// 		return true;
	// 	}catch(Exception $e){
	// 		$stmt->close();
	// 		return false;
	// 	}
	// }



	/* (non-PHPdoc)
	 * @see \modules\user\ILogin::findLoginByName()
	 */
	// public function findLoginByKey($key,$value) {
	// 	$instance = DBTool_ConnectDB::getInstance();
	// 	$sql = "select * from ".$this->getLogintable()." where ".$key."='".$value."' limit 1";
	// 	$stmt = $instance->conn->prepare($sql);
	// 	$result = $stmt->execute();
	// 	$stmt->bind_result($id,$user,$pass);
	// 	if($result) {		
	// 		while ($stmt->fetch()) {
	// 			if($user==$value)
	// 			{
	// 				return true;
	// 			}
	// 		}
	// 		return false;
	// 	}
	// 		return false;
		
	// }

	
	/* (non-PHPdoc)
	 * @see \modules\user\ILogin::deleteLoginById()
	 */
	// public function deleteLoginById($id) {
	// 	// TODO Auto-generated method stub
		
	// }

	// /* (non-PHPdoc)
	//  * @see \modules\user\ILogin::deleteLoginByName()
	//  */
	// public function deleteLoginByName($username) {
	// 	// TODO Auto-generated method stub
		
	// }

	//  (non-PHPdoc)
	//  * @see \modules\user\ILogin::findAllLogin()
	 
	// public function findAllLogin() {
	// 	// TODO Auto-generated method stub
		
	// }

	// /* (non-PHPdoc)
	//  * @see \modules\user\ILogin::findLoginById()
	//  */
	// public function findLoginById($id) {
	// 	// TODO Auto-generated method stub
		
	// }
	/**
	 * @return the $_logintable
	 */
	public function getLogintable() {
		return $this->_logintable;
	}

	/**
	 * @param string $_logintable
	 */
	public function setLogintable($_logintable) {
		$this->_logintable = $_logintable;
	}


	
}

