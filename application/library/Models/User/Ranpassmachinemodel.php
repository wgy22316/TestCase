<?php
// include 'Models_User_IMenu.php';

class Models_User_Ranpassmachinemodel implements Models_User_IRanpassmachine{
	/* (non-PHPdoc)
	 * @see IMenu::findAllMenu()
	 */
	public function BringPassword(){
		$password ='';
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		for ( $i = 0;$i < 8; $i++ )  
		{  
			// 这里提供两种字符获取方式  
			// 第一种是使用 substr 截取$chars中的任意一位字符；  
			// 第二种是取字符数组 $chars 的任意元素  
			$password .= substr($chars,mt_rand(0,strlen($chars)-1),1);  
			// $password .= $this->$chars[ mt_rand(0, strlen($this->$chars) - 1) ];  
		}  

		return $password; 

	}
	
}