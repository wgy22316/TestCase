<?php

class SearchController extends Yaf_Controller_Abstract{
	

	//跳转到搜索界面
	public function tosearchAction(){
		$game_v_id = $_GET['gameVersionId'];
		$task_id = $_GET['task_id'];
		$this->getView()->assign(['game_v_id'=>$game_v_id,'task_id'=>$task_id]);
	}
	/**
	 * 搜索用例ajax请求
	 * zhoujx
	 * 1.判断获取的参数是否为空
	 * 	如果为空 return fail
	 * 2.判断输入参数的合法性
	 * 	如果不合法 return fail
	 * 3.获取当前环境的模块
	 * 4.到数据库中查询搜索相关的内容
	 * 5.判断搜索结果是否为空
	 * 	如果为空 return fail
	 * 6.返回搜索结果
	 */
	public function searchAction(){
		if($this->getRequest()->isXmlHttpRequest()){
	   		$searchValue = $this->getRequest()->getPost('searchValue');
	   		if($searchValue==null||$searchValue==''){
				$array = array("state"=>"fail","error"=>"请输入需要搜索的内容");
	   		}
	   		else {
				$valida = Models_User_Validationmodel::getInstance();
	   			if(($valida->checkSpecialChar($searchValue)&&$valida->checkSqlInject($searchValue))) {
	   				$search = new Models_Case_Search();

	   				$game_id=Yaf_Session::getInstance()->get("game_id");//将用户的user_id放入session中;//需要从session中获取
	   				$searchresult = $search->findSearch($searchValue,$game_id);
	   				if($searchresult==null||$searchresult==''){
						$array = array("state"=>"fail","error"=>"搜索结果为空");
	   				}
	   				else{
						$array = array("state"=>"success","searchresult"=>$searchresult);
					}
	   			}
	   			else{
					$array = array("state"=>"fail","error"=>"请输入合法的内容");
	   			}
	   		}
			echo json_encode($array);
		}
		return false;
	}
	/**
	 * 搜索用例ajax请求
	 * zhoujx
	 * 1.判断获取的参数是否为空
	 * 	如果为空 return fail
	 * 2.判断输入参数的合法性
	 * 	如果不合法 return fail
	 * 3.根据id到数据库查询相应的用例
	 * 4.判断用例结果是否为空
	 * 	如果为空 return fail
	 * 5.返回用例结果
	 */
	public function detailAction(){
		if($this->getRequest()->isXmlHttpRequest()){
	   		$id = $this->getRequest()->getPost('id');
	   		if($id==null||$id==''){
				$array = array("state"=>"fail","error"=>"请输入需要用例ID");
	   		}
	   		else {
				$valida = Models_User_Validationmodel::getInstance();
	   			if(($valida->checkSpecialChar($id)&&$valida->checkSqlInject($id))) {
	   				$search = new Models_Case_Search();
	   				$searchresult = $search->findDetail($id);
	   				if($searchresult==null||$searchresult==''){
						$array = array("state"=>"fail","error"=>"搜索结果为空");
	   				}
	   				else{
						$array = array("state"=>"success","searchresult"=>$searchresult);
					}
	   			}
	   			else{
					$array = array("state"=>"fail","error"=>"请输入合法的内容");

	   			}
	   		}
			echo json_encode($array);
		}
		return false;
	}
	/**
	 * 搜索用例ajax请求
	 * zhoujx
	 * 1.判断获取的参数是否为空
	 * 	如果为空 return fail
	 * 2.判断输入参数的合法性
	 * 	如果不合法 return fail
	 * 3.根据id到数据库查询相应的用例
	 * 4.判断用例结果是否为空
	 * 	如果为空 return fail
	 * 5.返回用例结果
	 */
	public function addAction(){	
// 		if($this->getRequest()->isXmlHttpRequest()){
// 	   		$id = $this->getRequest()->getPost('id');
// 	   		$game_v_id = $_POST['game_v_id'];
// 	   		$task_id = $_POST['task_id'];
// 	   		if($id==null||$id==''){
// 				$array = array("state"=>"fail","error"=>"请输入需要用例ID");
// 	   		}
// 	   		else {
// 				$valida = Models_User_Validationmodel::getInstance();
// 	   			if(($valida->checkSpecialChar($id)&&$valida->checkSqlInject($id))) {
// 	   				$search = new Models_Case_Search();
// 	   				$searchresult = $search->addMyCase($id,$task_id,$game_v_id);
// 	   				if($searchresult["state"]=='success'){
// 						$array = array("state"=>"success");
// 	   				}
// 	   				else{
// 						$array = array("state"=>"fail","error"=>$searchresult["error"]);
// 					}
// 	   			}
// 	   			else{
// 					$array = array("state"=>"fail","error"=>"请输入合法的内容");

// 	   			}
// 	   		}
// 			echo json_encode($array);
// 		}
// 		return false;
	}

}

