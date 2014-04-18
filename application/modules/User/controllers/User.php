<?php

class UserController extends Yaf_Controller_Abstract{
	
	
	public function indexAction(){
			$this->getView();
// 		$this->getView();
// 		$this->getView()->display('login.phtml');
	}

	//
	public function infomationAction(){
			$this->getView();

	}

	//
	public function modpassAction(){
			$this->getView();

	}

	//
	public function noteAction(){
			$this->getView();

	}

	//
	public function addnoteAction(){
			$this->getView();

	}

	//
	public function emailAction(){
			$this->getView();
	}
	public function modpasswordAction(){

		if($this->getRequest()->isXmlHttpRequest()){

				$user_id = Yaf_Session::getInstance()->get('user_id');//用户ID
				$password = $this->getRequest()->getPost('password');//旧密码
				$modpassword = $this->getRequest()->getPost('modpassword');//新密码
				$modrpassword = $this->getRequest()->getPost('modrpassword');//重复新密码
				$modemail = $this->getRequest()->getPost('modemail');//email
				$user = new Models_User_Userinfo();

				$user->setUser_id($user_id);
				$user->setPassword($password);
				$user->setRpassword($password);
				$user->setEmail($modemail);

				$valida = Models_User_Validationmodel::getInstance();
				$checkresult = $valida->checkRegUser($user);//校验需要修改的用户信息

				if($checkresult==null){
					//判断用户名是否存在
					$user->setPassword(md5($password));
					$userModel =new Models_User_Usermodel();

					if(!$userModel->isUser($user)) {
						$resarray = array("state"=>"fail", "error"=>"请输入正确的原密码");
					}
					else if(!($userModel->isExistByKey("email",$user->getEmail()))) {
						$resarray = array("state"=>"fail", "error"=>"请输入本站注册使用的email");
					}else {
						$user->setPassword($modpassword);
						$user->setRpassword($modrpassword);
						$checkresult = $valida->checkRegUser($user);
						if($checkresult==null){
							//将用户修改信息入库
							$user->setPassword(md5($modpassword));
							$isupdate = $userModel->updateUser($user);
						    $sendmodel = new Models_User_Sendemail();
							$issend = $sendmodel->sendEmail($user->getEmail(),$modpassword,"修改密码");
							if($isupdate&&$issend){
									$resarray = array("state"=>"success");
							}else{
								// Yaf_Session::getInstance()->set("username",$user->getUsername());
								$resarray = array("state"=>"fail", "error"=>"修改失败，请重试");
							}
						}else{
							$resarray = array("state"=>"fail", "error"=>$checkresult);
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

	public function overstepAction(){

		// $pri = Yaf_Session::getInstance()->get('pri');//是否是越权操作
			$pri = $this->getRequest()->getParam('overstep');//新密码
		// if($pri!=null||$pri!=''){
			// printf("--");
			// printf($pri);
			// printf("--");
		// }

			$this->getView();
	}

	// public function getnoteAction(){

	// 	if($this->getRequest()->isXmlHttpRequest()){
			
	// 		$userModel = new Models_User_Usermodel();
	// 		$username = Yaf_Session::getInstance()->get('username');
	// 		if($username==null){
	// 			$array = array("state"=>"unlogin","error"=>"你还未登录，请返回登录");
	// 		}
	// 		else {
	// 			$arraynote = null;
	// 			$arraynote = $userModel->findAllNote($username);
	// 			if($arraynote!=null){
	// 				$array = array("state"=>"success","note"=>$arraynote);
	// 			}else{
	// 			$array = array("state"=>"fail","error"=>"备忘录初始化失败");
	// 			}
	// 		}
			
	// 		// print_r($array);
	// 		echo json_encode($array);
	// 	}
	// 	return false;

	// }
	// public function addnotesubAction(){

	// 	if($this->getRequest()->isXmlHttpRequest()){
	// 		$userModel = new Models_User_Usermodel();
	// 		$username = Yaf_Session::getInstance()->get('username');
	// 		if($username==null){
	// 			$array = array("state"=>"unlogin","error"=>"你还未登录，请返回登录");
	// 		}
	// 		else {
	// 			$instance = Models_User_Validationmodel::getInstance();
	// 			$title = $this->getRequest()->getPost('title');//姓名
	// 			$content = $this->getRequest()->getPost('content');
	// 			$remarks = $this->getRequest()->getPost('remarks');//用户名
	// 			$note = new Models_User_Note($username,$title,$content,(date('Y-m-d',time())),$remarks);
	// 			$result = $userModel->addnote($note);
	// 			if($result){
	// 				$array = array("state"=>"success");
	// 			}else{
	// 				$array = array("state"=>"fail","error"=>"添加失败");
	// 			}
	// 		}
			
	// 		// print_r($array);
	// 		echo json_encode($array);
	// 	}
	// 	return false;

	// }
}