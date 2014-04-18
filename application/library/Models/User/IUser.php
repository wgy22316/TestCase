<?php


// include 'Login.php';

interface Models_User_IUser {
	/**
	 * 判断是否为本网站用户
	 * input:user object
	 * output:true 是本站用户 ,false不是本站用户
	 */
	public function isUser($user);
	/**
	 *将注册信息存入数据库 
	 * input:user实例化对象
	 * output:true 存入成功,false存入失败
	 */
	public function addUser($user);
	/**
	 *判断数据库中是否存在key字段值为value的记录
	 * input：key列名，value列值
	 * output:true 存在 ,false不存在
	 *
	*/
	public function isExistByKey($key,$value);
	/**
	 *通过Email修改密码
	 * input：newpass新密码，email邮箱
	 * output:true 修改成功 ,false修改失败
	*/
	public function updatePassword($newpass,$email);
	/**
	 * 更新用户密码
	 * input: $user:username password not null
	 * ouput: true false
	 */
	public function updateUser($user);
	
}

?>