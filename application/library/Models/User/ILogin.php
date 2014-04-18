<?php

// namespace user\ILogin;
interface Models_User_ILogin {
	
	/**
	 * 判断是否为本网站用户
	 * input:user object
	 * output:username  用户的名称 ,null 不是本站用户
	 */
	public function findUser($user);
	/**
	*添加用户登陆信息
	*根据输入参数username修改登陆password信息
	*input：$username ，$password
	*output：true添加成功  false添加失败
	*/
	// public function addLogin($username,$password);

	
	/**
	 *判断数据库中是否存在key字段值为value的记录 
	 * input：key列名，value列值
	 * output:true 存在 ,false不存在
	 * 
	 */
	// public function findLoginByKey($key,$value);
	/**
	*更新用户登陆信息
	*根据输入参数username修改登陆password信息
	*input：$username ，$password
	*output：true修改成功  false修改失败
	*/
	// public function updateLogin($username,$password);


	
	// //通过Id查找用户登陆信息
	// public function findLoginById($id);
	// //查找所有用户登陆信息
	// public function findAllLogin();
	// //通过Id删除用户登陆信息
	// public function deleteLoginById($id);
	// //通过name删除用户登陆信息
	// public function deleteLoginByName($username);
	
	
	
}
