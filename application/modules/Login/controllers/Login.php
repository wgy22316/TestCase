<?php

class LoginController extends Yaf_Controller_Abstract{
	
	
	public function indexAction(){
	}

	//登陆页面
	public function loginAction(){
		$this->getView();
	}


	/**
	 * 登陆验证
	 * zhoujx
	 * 如果是本站用户
		 * 1.判断是否选择记住密码，如果选择，将密码md5后返回用户，保存致用户的cookie
		 * 2.将用户的id以及用户的姓名放入session中
		 * 3.session中user_id对应用户id、username对应用户名
		 * 4.获得用户的权限菜单
		 * 5.获得用户拥有的模块
	 * 如果 不是
	 * 1.返回失败
	 */
	public function loginCkAction()
	{
		//判断是否为ajax请求
		if($this->getRequest()->isXmlHttpRequest()){
	   		$user_id = $this->getRequest()->getPost('user_id');	
	   		$password = $this->getRequest()->getPost('password');
	   		$rem_login = $this->getRequest()->getPost('rem_login');	
			$user = new Models_User_Userinfo();//将用户输入信息封成对象
			$password  = md5($password);
			$user->setUser_id($user_id);
			$user->setPassword($password);
	   		$loginModel =new Models_User_Loginmodel();
	   		$username = $loginModel->findUser($user);
	   		if($username!=null&&$username!=''){
	   			if($rem_login){
	   				$array = array("state"=>"success","pass"=>($user->getPassword()));	 
	   			}
	   			else{
	   				$array = array("state"=>"success");	 
	   			}
	   			Yaf_Session::getInstance()->set("user_id",$user->getUser_id());//将用户的user_id放入session中
	   			Yaf_Session::getInstance()->set("username",$username);//将用户的username放入session中

	   			$this->getPrivilege();
	   			$this->getModule($user_id);
	   			
	   		}else{
	   			$array = array("state"=>"fail");
	   		}
	   		echo json_encode($array);
			return false;
		}
	}
	/**
	 * 获取权限（前置条件：session中必须有user_id属性，既用户已登录）
	 * zhoujx
	 * 1.判断session中是否以及存在权限（priviarray）属性
	 * 	a.如果有，结束
	 * 	b.如果没有，
	 * 		1.通过sesssion中用户的user_id查询
	 * 		2.判断该用户所拥有的菜单是否为空
	 * 			如果不为空  将用户的权限放入session中的priviarray
	 * */
	public function getPrivilege(){
			$user_id = Yaf_Session::getInstance()->get('user_id');
			$menu = new Models_User_Menu();
			$menuarray =  $menu->findMenu($user_id);//查询user_id用户的权限
			if($menuarray!=null&&$menuarray!=''){
				Yaf_Session::getInstance()->set('priviarray',$menuarray);//将用户拥有的菜单和ajax请求总和放入session
			}
	}

	/**
	 * 获取模块（前置条件：session中必须有user_id属性，既用户已登录）
	 * zhoujx
	 * 1.判断session中是否以及存在模块（module）属性
	 * 	a.如果有，结束
	 * 	b.如果没有，
	 * 		1.通过sesssion中用户的user_id查询
	 * 		2.将用户的权限放入session中的module
	 * 			如果模块为空
	 * 			如果模块不为空
	 * 				将模块的第一个值放入session中game_id，即设置默认当前游戏
	 * */
	public function getModule($user_id){
		$menu = new Models_User_Menu();
		$module = $menu->getModule($user_id);
		if($module!=null||$module!=''){
			Yaf_Session::getInstance()->set('module',$module);//
			$game_id = $module[0]['module_id'];
			Yaf_Session::getInstance()->set('game_id',$game_id);//放入默认的模块名
		}
	}
	
	/**
	 * 验证自动登陆（前置条件：用户的cookie中存在本站保存的账号密码）
	 * zhoujx
	 * 如果是本站用户
		 * 1.判断是否选择记住密码，如果选择，将密码md5后返回用户，保存致用户的cookie
		 * 2.将用户的id以及用户的姓名放入session中
		 * 3.session中user_id对应用户id、username对应用户名
		 * 4.获得用户的权限菜单
		 * 5.获得用户拥有的模块
	 * 如果 不是
	 * 1.返回失败
	 */
	public function loginautoCkAction()
	{
		//判断是否为ajax请求
		if($this->getRequest()->isXmlHttpRequest()){
	   		$user_id = $this->getRequest()->getPost('user_id');	
	   		$password = $this->getRequest()->getPost('password');

			$user = new Models_User_Userinfo();//将用户输入信息封成对象
			$user->setUser_id($user_id);
			$user->setPassword($password);
	   		$loginModel =new Models_User_Loginmodel();
	   		$username = $loginModel->findUser($user);

	   		if($username!=null&&$username!=''){
	   			$array = array("state"=>"success");	 
	   			Yaf_Session::getInstance()->set("user_id",$user->getUser_id());//将用户的user_id放入session中
	   			Yaf_Session::getInstance()->set("username",$username);//将用户的username放入session中
	   			$this->getPrivilege();
	   			$this->getModule($user_id);
	   		}else{
	   			$array = array("state"=>"fail");
	   		}
	   		echo json_encode($array);
			return false;
		}
	}

	/**
	 * 忘记密码
	 * zhoujx
	 * 1.判断输入email是否为空
	 * 	如果是 ，return fail
	 * 	如果不是
	 * 		1.验证email的合法性
	 * 			如果不合法 return fail
	 *  		如果合法
	 * 				判断该邮箱是否已注册本网站
	 * 					如果未注册 return fail
	 * 					已注册
	 * 						1.随机产生一个密码
	 * 						2.md5后存入数据库
	 * 						3.给该email发送新密码
	 * 				
	 * */
	public function forgotAction()
	{
		//判断是否为ajax请求
		if($this->getRequest()->isXmlHttpRequest()){
			$email = $this->getRequest()->getPost('email');
			if($email!=null&&$email!=''){
				$valida = Models_User_Validationmodel::getInstance();
				if($valida->checkEmail($email)){//判断输入email是否合法
					$userModel =new Models_User_Usermodel();
					if(!($userModel->isExistByKey("email",$email))) {//email是否在本站注册过
						$resarray = array("state"=>"fail", "error"=>"你输入的email并未注册本网站");
					}
					else{
						$sendmodel = new Models_User_Sendemail();
						$bringmodel = new Models_User_Ranpassmachinemodel();
						$newpass = $bringmodel->BringPassword();
						$password  = md5($newpass);//加密
						$issend = $sendmodel->sendEmail($email,$newpass,"重置密码");//发email给$email用户
						$isupdate = $userModel->updatePassword($password,$email);//更新数据库用户密码
						if($issend&&$isupdate){
							$resarray = array("state"=>"success");
						}
						else{
							$resarray = array("state"=>"fail", "error"=>"更新错误，请重试");
						}
					}
				}
				else{
					$resarray = array("state"=>"fail", "error"=>"请输入正确的Email");
				}
	   		}else{
				$resarray = array("state"=>"fail", "error"=>"请输入Email");
	   		}
		}
	   	echo json_encode($resarray);
		return false;
	}

	/**
	 * 用户注册
	 * zhoujx
	 * 1.获取用户注册信息，检验输入信息的合法性
	 * 如果不合法 return fail
	 * 如果合法
	 * 	1.判断该用户id是否已经注册
	 * 		如果已经注册 return fail
	 * 		如果未注册
	 * 			1.判断该用户email是否已经注册
	 * 				如果已经注册 return fail
	 * 				如果未注册
	 * 					将该用户信息存入数据库
	 * 						存入失败  return fail
	 * 						存入成功
	 * 							1.将用户的user_id、username存入sesssion中
	 * 							2.用户用户的权限菜单、模块
	 *
	 * */
	public function regsiterCkAction()
	{
		//判断是否为ajax请求
		if($this->getRequest()->isXmlHttpRequest()){
			$userModel =new Models_User_Usermodel();
			$valida = Models_User_Validationmodel::getInstance();

			$res_username = $this->getRequest()->getPost('res_username');//姓名
			$res_email = $this->getRequest()->getPost('res_email');
			$res_user_id = $this->getRequest()->getPost('res_user_id');//用户名
			$res_password = $this->getRequest()->getPost('res_password');//密码
			$res_rpassword = $this->getRequest()->getPost('res_rpassword');//重复密码
			$user = new Models_User_Userinfo();//将用户输入信息封成对象
			$user->UserInfo($res_user_id,$res_username,$res_password,$res_rpassword,$res_email);
			$checkresult = $valida->checkRegUser($user);//验证输入信息的合法性
			if($checkresult==null||$checkresult==''){
				//判断用户名是否存在
				if($userModel->isExistByKey("user_id",$user->getUser_id())){//检查用户名是否存在
					$resarray = array("state"=>"fail", "error"=>"你输入的用户名已经被使用了");
				}
				else if($userModel->isExistByKey("email",$user->getEmail())){//检查email是否已经被注册过
					$resarray = array("state"=>"fail", "error"=>"你输入的email已经被注册过了");
				}else {
					$user->setPassword(md5($res_password));
					//将用户注册数据实例化，存入数据库
					if(!$userModel->addUser($user)){
						$resarray = array("state"=>"fail", "error"=>"注册失败");
					}else{
			   			Yaf_Session::getInstance()->set("user_id",$user->getUser_id());//将用户的user_id放入session中
			   			Yaf_Session::getInstance()->set("username",$user->getUsername());//将用户的username放入session中
	   					$this->getPrivilege();
	   					$this->getModule($user->getUser_id());
						$resarray = array("state"=>"success");
					}
				}
				echo json_encode($resarray);
			}else{
				// $resarray = array("state"=>"fail", "error"=>"请输入正确的信息");
				$resarray = array("state"=>"fail", "error"=>$checkresult);
				echo json_encode($resarray);
			}
		}
		return false;
		
	}
	
	/**
	 * 越权跳转页面
	 * zhoujx
	 * */
	public function ytqrAction(){
		$this->getView();
	}

}

