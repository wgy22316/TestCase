<?php
class Models_Authority_Privilege{

	//**************************【共用的方法】 start **********************************************
	/**
	*  生成父子菜单Tree
	*/
	function generateTree($menuarray){
		
		 $tree = array();
		 $items = array();
		 $length=$this->maxmenu_id($menuarray);
		 for($i=0,$length;$i<$length;$i++){
		 	$items[$i+1]=$this->menuindex($i+1,$menuarray);
		 }

		 // print_r($items);
		 foreach($items as $item){
			 if(isset($items[$item['menu_pid']])){//判断items节点是否有子节点
			 	// print_r($items[$item['menu_id']]);
				 $items[$item['menu_pid']]['son'][] = &$items[$item['menu_id']];//把子节点加入本父节点中
			 }else{
				 $tree[] = &$items[$item['menu_id']];
			 }
		 }
		 // print_r($tree);
		for($i=0,$length=sizeof($tree);$i<$length;$i++){
			if($tree[$i]['menu_id']==null)
				unset($tree[$i]);
		}
		$tree = array_values($tree);
		return $tree;
	 }
	//找出最大的menu_id
	function maxmenu_id($menuarray){
		$max=0;
		for($i=0,$length=sizeof($menuarray);$i<$length;$i++){
			if($max<$menuarray[$i]['menu_id'])
				$max=$menuarray[$i]['menu_id'];
		 }
		 return $max;
	}
	//将数组下标和menu_id相同
	function menuindex($index,$menuarray){
		for($i=0,$length=sizeof($menuarray);$i<$length;$i++){
			if($index==$menuarray[$i]['menu_id'])
				return $menuarray[$i];
		 }
	}
	//*********************【共用的方法】 end******************************************

	/***************************特殊权限分配 model层 start*****************************/
	/**
	*  获取用户的特殊权限
	*/
	public function getSpecialPrivilege($user_id){
		$DBUtil =new DBTool_DBUtil();
		//print_r($user_id);
		if($user_id=='0'){
			$sql="SELECT * FROM tc_menu where menu_id";
		}else{
			$sql ="SELECT privilege FROM tc_user_privilege where user_id='".$user_id."'";
			$res=$DBUtil->executeDQL($sql,MYSQL_NUM);
			//$menu_ids_arr=explode(",", $res[0][0]); //菜单ID的数组
			// $sql ="SELECT menu_id,menu_pid,menu_name FROM tc_menu where menu_id in (".$res[0][0].")";
			$sql ="SELECT * FROM tc_menu where menu_id in (".$res[0][0].")";
			file_put_contents("SQL.log", $sql."\n\r",FILE_APPEND);	
		}
		$res=$DBUtil->executeDQL($sql,MYSQL_ASSOC);
		return $this->generateTree($res);
	}
	
	/**
	*   获取勾选的用户和登陆用户相同部分的特殊权限
	*/
	public function getSamePrivilege($user_id,$user_id_target){
		$DBUtil =new DBTool_DBUtil();
		if($user_id=='0'){
			$sql="SELECT menu_id From tc_menu";
			$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);
			$user_privilege=array();
			foreach ($res as $key => $value) {
				$user_privilege[]=$value['menu_id'];
			}
			$user_privilege=implode(",",$user_privilege);
		}else{
			$sql ="SELECT privilege FROM tc_user_privilege where user_id='".$user_id."'";
			$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);
			$user_privilege =$res[0]['privilege'];
		//	print_r($user_privilege);
		}
		/*  root用户不会作为target被搜索出来，这里可以省略
		if($user_id_target=='0'){
			$sql="SELECT menu_id From tc_menu";
			$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);
			$user_target_privilege=array();
			foreach ($res as $key => $value) {
				$user_target_privilege[]=$value['menu_id'];
			}
			$user_target_privilege=implode(",",$user_target_privilege);
		}else{
			$sql2="SELECT privilege FROM tc_user_privilege where user_id='".$user_id_target."'";
			$res2=$DBUtil->executeDQL($sql2,MYSQL_ASSOC);
			$user_target_privilege =$res2[0]['privilege'];
		}
		*/
		$sql2="SELECT privilege FROM tc_user_privilege where user_id='".$user_id_target."'";
		$res2=$DBUtil->executeDQL($sql2,MYSQL_ASSOC);
		$user_target_privilege =$res2[0]['privilege'];
		//print_r($res2);
		if($user_target_privilege){
			$arr_target =explode(",", $user_target_privilege);
			$arr =explode(",", $user_privilege);
		//	print_r($arr_target);
		//	print_r($arr);
			$arr_same =array();   //相同的权限
			foreach ($arr_target as $v) {
				foreach ($arr as $value) {
					if($v==$value){
						$arr_same[]=$v;
						break;
					}
				}
			}
			//print_r($arr_same);
			return $arr_same;
		}else{
			return false;
		}
	}
	/**
	*   更新个人特殊权限
	*/
	public function updateSpecialPrivilege($user_id,$user_id_target,$menu_ids){
		$DBUtil =new DBTool_DBUtil();
		$sql ="SELECT privilege FROM tc_user_privilege where user_id='".$user_id."'";
		$sql2 ="SELECT privilege FROM tc_user_privilege where user_id='".$user_id_target."'";
		
		$res=$DBUtil->executeDQL($sql,MYSQL_ASSOC);
		$user_privilege=$res[0]['privilege'];
		$arr_user=explode(",", $user_privilege);

		$res2=$DBUtil->executeDQL($sql2,MYSQL_ASSOC);
		$user_target_privilege=$res2[0]['privilege'];
		$arr_target=explode(",", $user_target_privilege);

		$menu_ids_str ="";
		if($user_target_privilege){
			$arr_diff =array_diff($arr_target, $arr_user);
			$finalArr=array_unique(array_merge($arr_diff,$menu_ids));
			$menu_ids_str=implode(",", $finalArr);
		}else{
			$menu_ids_str=implode(",", $menu_ids);
		}	
		//echo $menu_ids_str;
		$sql="update tc_user_privilege set privilege ='".$menu_ids_str."' where user_id='".$user_id_target."'";
		$res=$DBUtil->executeDML($sql);
		return $res;
	}
	/**
	*	获取用户已有的权限
	*/
	public function getMyPrivilege($user_id){
		$menusModel =new Models_User_Privilege();
		$DBUtil =new DBTool_DBUtil();
		
		$myMenu=$menusModel->findMenu($user_id);  
		//var_dump($myMenu);
		// $menus=array();
		// $sameMenus=array();
		// foreach ($myMenu as $key => $value) {
		// 	$menus[]=$value;
		// }
		return array(
				"menus"=>$this->generateTree($myMenu),
		);
		
	}
	/******************************特殊权限分配 model层 end****************************************/

	//**************************【共用的方法】 start **********************************************
	/**
	*  分配组权限和特殊权限都需要用到的 实时查询用户
	*/
	public function findUser($user_id_target){
		$DBUtil =new DBTool_DBUtil();
		// $sql="SELECT user_id,username FROM tc_userinfo where user_id='".$user_id_target."'";
		$sql="SELECT user_id,username FROM tc_userinfo where (user_id like'".$user_id_target."%'";
		$sql.=" or username like '".$user_id_target."%') and user_id!='0'";   //过滤掉root用户，以防别人更改root用户的权限
		// print_r($sql);
		//file_put_contents("SQL.log", $sql."\r\n",FILE_APPEND);
		$res=$DBUtil->executeDQL($sql,MYSQL_ASSOC);
		if(!$res){
			$res=false;
		}
		return array(
			"targetName"=>$res,
		);
	}
	//*********************【共用的方法】 end******************************************

	/*************************新的组权限分配 model层 start****************************/
	/**
	* 获取用户的组
	*/
	public function getGroup($user_id){
		$DBUtil = new DBTool_DBUtil();
		if($user_id=='0'){
			$group_ids="SELECT group_id FROM tc_group";
			$res=$DBUtil->executeDQL($group_ids,MYSQLI_ASSOC);
			$arr_group_id=array();
			foreach ($res as $key => $value) {
				$arr_group_id[]=$value['group_id'];
			}	
			//print_r($res);
		}else{
			$group_ids="SELECT group_id FROM tc_user_privilege where user_id='".$user_id."'";
			$res=$DBUtil->executeDQL($group_ids,MYSQL_NUM);
			$arr_group_id=explode(",", $res[0][0]);
		}
		//print_r($arr_group_id);
		sort($arr_group_id);
		return $arr_group_id;
	}
	/**
	*	获取组下面的菜单
	*/
	public function getMenuUnderGroup($group_id){
		$DBUtil=new DBTool_DBUtil();
		$sql="SELECT menu_id,group_name,group_id FROM tc_group where group_id='".$group_id."'";
		$res=$DBUtil->executeDQL($sql,MYSQLI_NUM);
		$group_name=$res[0][1];
		$group_id=$res[0][2];
		if($res[0][0]){
			$sql="SELECT menu_id,menu_pid,menu_name FROM tc_menu where menu_id in(".$res[0][0].")";
			$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);
			$res=array(
				"group_id"=>$group_id,
				"group_name"=>$group_name,
				"menuData"=>$this->generateTree($res),
			);
		}else{
			$res=array(
				"group_id"=>$group_id,
				"group_name"=>$group_name,
				"menuData"=>"",
			);
		}
		
		return $res;
		// return $this->generateTree($res);
	}
	/**
	*  获取用户的组
	*/
	public function getGroupByUserId($user_id_target){
		$DBUtil=new DBTool_DBUtil();
		$sql="SELECT group_id FROM tc_user_privilege where user_id ='".$user_id_target."'";
		$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);
		$group_ids=explode(",", $res[0]['group_id']);
		return $group_ids;
	}
	/**
	*	更新target的Group ID,先得到target特有的组，在和前台传来的组合并
	*/
	public function updateGroup($user_id,$user_id_target,$group_ids){
		$DBUtil=new DBTool_DBUtil();
		$arr_user_group=$this->getGroupByUserId($user_id);
		$arr_target_group=$this->getGroupByUserId($user_id_target);
		
		$diff_group=array_diff($arr_target_group,$arr_user_group);    //target user特有的组
		
		$final_group_ids=array();
		if(!$group_ids){
			$final_group_ids=$diff_group;
		}else{
			$final_group_ids=array_merge($diff_group,$group_ids);
		}
		$final_group_ids=array_unique($final_group_ids);   //去重
		$final_group_ids=implode(",",$final_group_ids);
		$sql="";
		if($final_group_ids){
			$sql ="SELECT module_id FROM tc_group where group_id in (".$final_group_ids.")";
			$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);

			$module_ids=array();
			foreach ($res as $key => $value) {
				$module_ids[]=$value['module_id'];
			}
			$module_ids[]=0;   //保证任何用户都有0,1,模块（游戏分开之后)
			$module_ids[]=1;   
			$module_ids=array_unique($module_ids);
			$module_ids=implode(",",$module_ids);   //获得组所对应的module_id
			$sql="update tc_user_privilege set group_id='".$final_group_ids."',module_id='".$module_ids."' where user_id='".$user_id_target."'";
			// $res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);
			// print_r($res);
		}else{
			$sql="update tc_user_privilege set group_id='1000',module_id='0,1' where user_id='".$user_id_target."'";
		}
		file_put_contents("SQL.log", "最后执行的==".$sql."\r\n",FILE_APPEND);
		$res=$DBUtil->executeDML($sql);
		return $res;
	}
	/******************新的组权限分配 model层 end ***************************************/

	/******************权限分组  model层  start *****************************************/
	/**
	*  获取权限组信息，
	*/
	public function getGroups($group_id=null){
			$DBUtil=new DBTool_DBUtil();
			$sql ="SELECT group_id,group_name,module_name FROM tc_group";
			$sql.=" LEFT JOIN tc_module on tc_group.module_id=tc_module.module_id";
			
			$res=$DBUtil->executeDQL($sql,MYSQLI_NUM);
			foreach ($res as $key => $value) {
				$res[$key][]=$value[0];  //存放ID给查看用户
				$res[$key][]=$value[0];  //存放ID给编辑按钮
				$res[$key][]=$value[0];  //存放ID给删除按钮
			}
			return $res;
		
	}
	/**
	* 获取groop_id的组信息 
	*/
	public function getGroupById($group_id){
		$DBUtil=new DBTool_DBUtil();
		$sql="SELECT * FROM tc_group where group_id='".$group_id."'";
		$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);
		return $res;
	}
	/**
	* 获取所有的菜单----组管理中没有使用
	*/
	/*
	public function getAllMenus(){
		$DBUtil=new DBTool_DBUtil();
		$sql="SELECT menu_id,menu_pid,menu_name FROM tc_menu";
		$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);	
		return $this->generateTree($res);
	}
	*/
	/**
	*  获得组内菜单,并勾选
	*/
	public function getMenuByGroup($menu_ids){
		// $arr_menu_ids=explode(",", $menu_ids);
		$DBUtil=new DBTool_DBUtil();
		$sql="SELECT menu_id,menu_pid,menu_name FROM tc_menu";
		$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);

		$sql="SELECT menu_id,menu_pid,menu_name FROM tc_menu where menu_id in(".$menu_ids.")";
		$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);
		return $res;
		// print_r($sql);
		// print_r($arr_menu_ids);

	}
	/**
	*  更新组内菜单
	*/
	public function updateGroupMenu($group_id,$group_name,$menu_ids){
		$DBUtil=new DBTool_DBUtil();
		$sql="update tc_group set group_name='".$group_name."',menu_id='".$menu_ids."' where group_id='".$group_id."'";
		$res=$DBUtil->executeDML($sql);
		return $res;
	}
	/**
	* 获取所有组总数，用于分页
	*/
	public function getGroupTotal(){
		$DBUtil=new DBTool_DBUtil();
		$sql="SELECT count(group_id) FROM tc_group";
		$res=$DBUtil->executeDQL($sql,MYSQLI_NUM);
		return $res[0][0];
	}
	/**
	*  删除组,同时删除用户权限中的组
	*/
	public function deleteGroupById($group_id){
		$DBUtil=new DBTool_DBUtil();
		$sql="SELECT user_id,group_id FROM tc_user_privilege where group_id like '%".$group_id."%'";
		file_put_contents("SQL.log", "查询用户的组ID--".$sql."\r\n",FILE_APPEND);

		$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);
		foreach ($res as $key => $value) {
			$ids=explode(",",$value['group_id']);
			//$ids=explode(",", $ids[0]);
			//print_r($ids);
			foreach ($ids as $k => $v) {
				if($group_id==$v){
					unset($ids[$k]);
					// $ids[$k]="";
				}
			}
			//print_r($ids);
			$ids =implode(",", $ids);
			//print_r($ids);
			$user_id=$value['user_id'];
			$update ="update tc_user_privilege set group_id='".$ids."' where user_id='".$user_id."'";
			// print_r($update);
			file_put_contents("SQL.log", "遍历并修改用户组ID--".$update."\r\n",FILE_APPEND);
			$DBUtil->executeDML($update);
		}
		$sql="delete FROM tc_group where group_id ='".$group_id."'";
		file_put_contents("SQL.log", "删除该组--".$sql."\r\n-----下一条-------\r\n",FILE_APPEND);
		$res=$DBUtil->executeDML($sql);
		return $res;
	}
	/**
	* 新添加组时，获取模块 
	*/
	public function getModule(){
		$DBUtil=new DBTool_DBUtil();
		$sql="SELECT * FROM tc_module ";
		return $DBUtil->executeDQL($sql,MYSQLI_ASSOC);
	}
	/**
	* 新建组时获取下拉框选中模块下的菜单
	*/
	public function getMenuUnderModule($module_id){
		$DBUtil=new DBTool_DBUtil();
		$sql="SELECT menu_id,menu_pid,menu_name FROM tc_menu where module_id='".$module_id."'";
		$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);	
		return $this->generateTree($res);
	}
	/**
	*  新建组
	*/	
	public function createGroup($user_id,$group_name,$module_id,$menu_ids){
		$DBUtil=new DBTool_DBUtil();
		//$sql="insert into tc_group values(null,'".$menu_ids."','".$group_name."','".$module_id."')";
		//print_r($sql);
		//exit();
		//$res=$DBUtil->executeDML($sql);
		//return $res;
		
		$sql="insert into tc_group values(null,?,?,?)";
		//**将新建的组id加到改登陆用户的权限中以便于其分配给其他人
		$inserted_group_id =$DBUtil->executeInsert($sql,array("ssi",$menu_ids,$group_name,$module_id));
		if($inserted_group_id=="-3"){  //出错
			return 2;
		}else{
			$sql="SELECT module_id,group_id FROM tc_user_privilege where user_id='".$user_id."'";
			$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);
			$self_module_id=$res[0]['module_id'];
			$self_module_id=explode(",", $self_module_id);
			$self_module_id[]=$module_id;
			$self_module_id=implode(",", array_unique($self_module_id));  //模块去重

			$self_group_id=$res[0]['group_id'];
			$self_group_id=explode(",",$self_group_id);
			$self_group_id[]=$inserted_group_id;
			$self_group_id=implode(",",$self_group_id);  //这个新添加的group_id是自动生成的新的，不存在去重问题

			$sql="update tc_user_privilege set module_id='".$self_module_id."',group_id='".$self_group_id."' where user_id='".$user_id."'";
			$res =$DBUtil->executeDML($sql);
			return $res;	
		}
		
	}
	/**
	* 获取组内用户
	*/
	public function getUserUnderGroup($group_id){
		$DBUtil=new DBTool_DBUtil();
		$sql="SELECT tc_userinfo.username FROM tc_user_privilege LEFT JOIN tc_userinfo ON tc_user_privilege.user_id=tc_userinfo.user_id
		where group_id like '%".$group_id."%'";
		$res=$DBUtil->executeDQL($sql,MYSQLI_ASSOC);
		return $res;
	}
	/***************************权限分组 model层  end********************************************************/
	
}