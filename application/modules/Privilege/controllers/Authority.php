<?php
/**
* @author songgl
*/
class AuthorityController extends Yaf_Controller_Abstract{
	/**
	* 显示用户的权限页面  old not used
	*/
	public function showpageAction(){
		$user_id=Yaf_Session::getInstance()->get("user_id");
		return $this->getView()->assign(["user_id"=>$user_id]);
	}
	/**
	*  显示组权限分配页面
	*/
	public function groupauthorityAction(){
		$user_id=Yaf_Session::getInstance()->get("user_id");
		$username=Yaf_Session::getInstance()->get("username");
		return $this->getView()->assign(["user_id"=>$user_id,"username"=>$username]);
	}
	/**
	* 显示个人特权分配页面
	*/
	public function specialauthorityAction(){
		$user_id=Yaf_Session::getInstance()->get("user_id");
		return $this->getView()->assign(["user_id"=>$user_id]);
	}
	
	/**
	* 获取自己的special权限
	*/
	public function showspecialprivilegeAction(){
		$user_id=$_POST['user_id'];
		$authorityModel =new Models_Authority_Privilege();
		$res=$authorityModel->getSpecialPrivilege($user_id);
		echo json_encode($res);
		return false;
	}
	/**
	* 获取自己已有的权限
	*/
	public function showmyprivilegeAction(){
		$user_id=$_POST['user_id'];
		$authorityModel =new Models_Authority_Privilege();
		$res=$authorityModel->getMyPrivilege($user_id);
		//print_r($res);
		echo json_encode($res);
		return false;
	}
	/**
	* 检查用户是否存在
	*/
	public function showusernameAction(){
		$user_id_target =$_POST['user_id_target'];
		$authorityModel =new Models_Authority_Privilege();
		$res=$authorityModel->findUser($user_id_target);
		echo json_encode($res);
		return false;
	}
	/**
	* 获取target user的权限，返回和登陆用户重复的
	*/
	public function gettargetspecialprivilegeAction(){
		$user_id_target =$_POST['user_id_target'];
		$user_id=$_POST['user_id'];
		$authorityModel =new Models_Authority_Privilege();
		$res=$authorityModel->getSamePrivilege($user_id,$user_id_target);
		echo json_encode($res);
		return false;
	}
	/**
	* 设置个人权限
	*/
	public function settingprivilegeAction(){
		$menu_ids=@$_POST['menu_ids'];
		if(!$menu_ids){
			$menu_ids=array();
		}
		$user_id=$_POST['user_id'];
		$user_id_target=$_POST['user_id_target'];
		$authorityModel =new Models_Authority_Privilege();

		Models_Log_Log::saveLog($user_id."变更了".$user_id_target."的个人特权==>".implode(",", $menu_ids));
		$res=$authorityModel->updateSpecialPrivilege($user_id,$user_id_target,$menu_ids);
		echo json_encode($res);
		// print_r(array_unique($menu_ids));
		//print_r($menu_ids);
		return false;
	}
	/****************************新的组权限分配 controller层 start**********************/
	/**
	* 获取用户的组权限22,返回特定格式的json数据
	*/
	public function showgroupprivilegeAction(){
		$user_id =$_POST['user_id'];
		$authorityModel =new Models_Authority_Privilege();
		$res=$authorityModel->getGroup($user_id);

		echo json_encode($res);
		return false;
	}
	/**
	*  获取组内的菜单，接收group_id[user_id已经在上一个方法中处理]
	*/
	public function listMenusUnderGroupAction(){
		$group_id=$_POST['group_id'];
		$authorityModel=new Models_Authority_Privilege();
		$res=$authorityModel->getMenuUnderGroup($group_id);
		echo json_encode($res);
		return false;
	}
	/**
	* 获取target user 的组ID
	*/
	public function gettargetgroupAction(){
		$user_id_target=$_POST['user_id_target'];
		$authorityModel=new Models_Authority_Privilege();
		$res=$authorityModel->getGroupByUserId($user_id_target);
		echo json_encode($res);
		return false;
	}
	/**
	* 更新target用户的组
	*/
	public function settinggroupAction(){
		$user_id_target=$_POST['user_id_target'];
		$user_id=$_POST['user_id'];
		$group_ids=@$_POST['group_ids'];
		$authorityModel=new Models_Authority_Privilege();

		Models_Log_Log::saveLog($user_id."变更了".$user_id_target."的组权限==>".implode(",", $group_ids));
		$res =$authorityModel->updateGroup($user_id,$user_id_target,$group_ids);
		echo json_encode($res);
		return false;
	}
	/**************************新的组权限分配 Controller层 end*********************************/
	/**
	*  显示权限分组管理页面
	*/
	public function groupmanageAction(){
		$user_id=Yaf_Session::getInstance()->get("user_id");
		return $this->getView()->assign(["user_id"=>$user_id]);
	}
	/**
	*  获取所有的组
	*/
	public function listAllGroupsAction(){
		$iDisplayStart=$_REQUEST['iDisplayStart'];
		$iDisplayLength=$_REQUEST['iDisplayLength'];
		$authorityModel=new Models_Authority_Privilege();
		$Total=$authorityModel->getGroupTotal();
		$res=$authorityModel->getGroups();
		$json=array(
			"iDisplayLength"=>$iDisplayLength,
			"iTotalRecords"=> $Total,//返回的总数据
			"iTotalDisplayRecords"=> $Total,  //过滤后的数据
			"aaData"=>$res,
		);
		echo json_encode($json);
		return false;
	}
    /**
    *  获取group_id的组信息
    */
    public function listGroupByIdAction(){
    	$group_id=$_POST['group_id'];
    	$authorityModel=new Models_Authority_Privilege();
    	$res=$authorityModel->getGroupById($group_id);
    	if($res){
    		echo json_encode(array(
    			"data"=>$res,
    			"status"=>"success",
    		));
    	}else{
    		echo json_encode(array(
    			"status"=>"error",
    		));		
    	}
    	return false;
    }
    /**
    * 获取组下面的菜单
    */
    public function listMenuByGroupAction(){
    	$menu_ids=$_POST['menu_ids'];  //前台传来的组里的菜单字符串
    	$authorityModel=new Models_Authority_Privilege();
    	$res=$authorityModel->getMenuByGroup($menu_ids);
    	echo json_encode($res);
    	return false;
    }
    /**
    *  获取所有菜单--组权限中没有使用
    */
    /*
    public function listAllMenusAction(){
    	$authorityModel=new Models_Authority_Privilege();
    	$res=$authorityModel->getAllMenus();
    	echo json_encode($res);
    	return false;
    }
   	*/
    public function listMenuUnderModuleAction(){
    	$module_id=$_POST['module_id'];
    	$authorityModel=new Models_Authority_Privilege();
    	$res=$authorityModel->getMenuUnderModule($module_id);
    	echo json_encode($res);
    	return false;
    }
    /**
    * 更新组里菜单
    */
    public function settingGroupMenuAction(){
    	$group_id=$_POST['group_id'];
    	$group_name=$_POST['group_name'];
    	$menu_ids=$_POST['menu_ids'];
    	$authorityModel=new Models_Authority_Privilege();
    	$menu_ids=implode(",", $menu_ids);

    	Models_Log_Log::saveLog("变更了".$group_name."[ID=".$group_id."]的组内权限==>".$menu_ids);
    	$res=$authorityModel->updateGroupMenu($group_id,$group_name,$menu_ids);
    	echo json_encode($res);
    	return false;
    }
	
	/**
	*  获取模块和菜单，填充对话框
	*/
	public function listModuleAction(){
		$authorityModel=new Models_Authority_Privilege();
		$res=$authorityModel->getModule();
		$json=array();
		if($res){
			$json=array(
				"status"=>"success",
				"data"=>$res,
			);
		}else{
			$json=array(
				"status"=>"error",
			);
		}
		echo json_encode($json);
		return false;	
	}
	/**
	*  新建组
	*/
	public function newGroupAction(){
		$group_name=@$_POST['group_name'];   //js验证
		$module_id=@$_POST['module_id'];    //js验证
		$menu_ids=@$_POST['menu_ids'];
		
		$user_id=Yaf_Session::getInstance()->get("user_id");
		$authorityModel=new Models_Authority_Privilege();
		
		if($menu_ids){
			$menu_ids=implode(",", $menu_ids);
		}
		$res=$authorityModel->createGroup($user_id,$group_name,$module_id,$menu_ids);
		echo json_encode($res);
		return false;
	}
	/**
	* 删除组
	*/
	public function delGroupAction(){
		$group_id=$_POST['group_id'];
		$authorityModel=new Models_Authority_Privilege();
		if($group_id=="1000"){  //共用组
			echo json_encode("2");
			// print_r("expression");
			die();
		}
		Models_Log_Log::saveLog("删除了[ID=".$group_id."]的组");
		$res =$authorityModel->deleteGroupById($group_id);
		echo json_encode($res);
		return false;
	}	
	/**
	* 获取组内用户
	*/
	public function listUserUnderGroupAction(){
		$group_id=@$_POST['group_id'];
		$authorityModel=new Models_Authority_Privilege();
		$res=$authorityModel->getUserUnderGroup($group_id);
		echo json_encode($res);
		return false;
	}	
}

