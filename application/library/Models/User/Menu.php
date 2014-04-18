<?php
class Models_User_Menu{

	/**
	 * 获取权限（前置条件：user_id既用户注册）
	 * zhoujx
	 * 1.判断是否是root用户、如果是，直接查询菜单表的所有菜单
	 * 2.查询用户所有用的模块、组、特权菜单
	 * 3.根据模块、组查询用户所拥有的菜单ID
	 * 4.将结果组合成menu_id到菜单表中查询所拥有的菜单
	 * 5.返回用户所拥有的菜单
	 * 
	 * */
	public function findMenu($user_id){
		try{
			if($user_id==0){
				$instance = new DBTool_DBUtil();
				$menu_sql ="select  module_id,menu_id,menu_pid,menu_name,menu_url ,menu_sort_id,menu_view from tc_menu  ;"; //用户所拥有的菜单
				$menu=$instance->executeDQL($menu_sql,MYSQL_ASSOC);
			}else{
				$instance = new DBTool_DBUtil();
				$sql = "select module_id,group_id,privilege from tc_user_privilege where user_id='".$user_id."' ;";
				$res=$instance->executeDQL($sql,MYSQL_ASSOC);
					$module_id=$res[0]['module_id']; //用户模块ID
					$group_id=$res[0]['group_id'];// 所在组id
					$special_menu_id=$res[0]['privilege']; //用户特有的菜单id
					// printf($group_id);
					$group_menu ="select  menu_id  from tc_group where group_id IN (".$group_id.") and module_id in (".$module_id.") ;"; //用户所在组的菜单
					$res=$instance->executeDQL($group_menu,MYSQL_ASSOC);
					$menu_ids=array();
					// print_r($res);
					foreach ($res as $key => $value) {
						foreach ($value as $key => $value) {
							if($value!=null&&$value!=''){
								$menu_ids[]=$value;
							}
						}
					}
					$menu_ids=implode(',',$menu_ids);//将查询的所拥有组的菜单转换为字符串
					$arr2 = explode( ',', $menu_ids);////将字符串换成数组
					if($special_menu_id!=null){
						$arr1 = explode( ',', $special_menu_id);//将字符串换成数组
						$menuarr=array_merge($arr1,$arr2);//合并菜单id
						$menuarr = array_unique($menuarr);//去重
						$menustr=implode(',',$menuarr);//将查询的所拥有组的菜单转换为字符串
					}else{
						$arr2 = array_unique($arr2);//去重
						$menustr=implode(',',$arr2);//将查询的所拥有组的菜单转换为字符串
					}
					$menu_sql ="select  module_id,menu_id,menu_pid,menu_name,menu_url ,menu_sort_id,menu_view from tc_menu where menu_id IN (".$menustr.")  ;"; //用户所拥有的菜单
					$menu=$instance->executeDQL($menu_sql,MYSQL_ASSOC);
			}
			if(empty($menu)){
				$instance->close_conn();
				return null;
			}
			else{
				$instance->close_conn();
				return $menu;
			}
		}catch(Exception $e){
			$instance->close_conn();
			return null;
		}

		$instance->close_conn();
		return null;
	}

	/**
	 * 更新菜单
	 * zhoujx
	 * 1.将接受的菜单组织成sql语句，执行
	 * 2.如果执行成功，将执行数据输出到日志表中  return true
	 * 3.如果执行失败，return false
	 *
	 * */
	public function updatemenu($menu){
		try{
			$instance = new DBTool_DBUtil();
	   		$id = $menu['id'];
	   		$module = $menu['module'];
	   		$url = $menu['url'];
	   		$pid = $menu['pid'];
	   		$name = $menu['name'];
	   		$sort_id = $menu['sort'];
	   		$view = $menu['view'];
			$query= "update tc_menu set menu_pid=".$pid." ,  menu_name='".$name."' ,menu_url='".$url."' ";
			$query .=", menu_sort_id=".$sort_id.", menu_view=".$view.", module_id=".$module." where menu_id=".$id." ;";

			$query_result =$instance->executeDML($query);
			if($query_result==1){
				$instance->close_conn();
				$log = new Models_Log_Log();
				$desc = "更新ID为".$id."的菜单--";
				$desc .="将模块更新为:".$module."、";
				$desc .="将父节点更新为:".$pid."、";
				$desc .="将菜单名更新为:".$name."、";
				$desc .="将菜单链接更新为:".$url."、";
				$desc .="将菜单排序ID更新为:".$sort_id."、";
				$desc .="将是否有视图更新为:".$view.";";
				$log->saveLog($desc);
				return true;
			}else{
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
	 * 删除菜单
	 * zhoujx
	 * 1.将接受的菜单组织成sql语句，执行
	 * 2.如果执行成功，将执行数据输出到日志表中  return true
	 * 3.如果执行失败，return false
	 * */
	public function deletemenu($id){
		try{
			$instance = new DBTool_DBUtil();
			$query= "delete from tc_menu where menu_id=".$id." ; ";
			$query_result =$instance->executeDML($query);
			if($query_result==1){
				$instance->close_conn();
				//将本次操作存入操作表中
				$log = new Models_Log_Log();
				$log->saveLog("删除ID为".$id."的菜单");
				return true;
			}else{
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
	 * 获取所有菜单
	 * zhoujx
	 * 1.从菜单表中获取所有菜单
	 * 2.如果结果为空，  return null
	 * 3.如果结果不为空，return menu
	 * */
	public function getAllMenu(){

		try{
			$instance = new DBTool_DBUtil();
			$menu_sql ="select menu_url  from tc_menu  ;"; //用户所拥有的菜单
			$menu=$instance->executeDQL($menu_sql,MYSQL_ASSOC);
			if(empty($menu)){
				$instance->close_conn();
				return null;
			}
			else{
				$instance->close_conn();
				return $menu;
			}
		}catch(Exception $e){
			$instance->close_conn();
			return null;
		}
		$instance->close_conn();
		return null;
	}
	/**
	 *获取用户拥有模块（除公有模块）
	 * zhoujx
	 * 1.判断是否是root用户、如果是，直接查询模块表中除公有模块的所有菜单
	 * 2.通过user_id到权限表中获取用户拥有的模块id
	 * 3.通过获取的模块id到模块表中查询用户的模块
	 * 4.如果结果不为空，return menu
	 * */
	public function getModule($user_id){
		try{
			if($user_id==0){
				$instance = new DBTool_DBUtil();
				$module_sql ="select  *  from tc_module where module_id !=0 ;"; //用户所在组的菜单
				$module=$instance->executeDQL($module_sql,MYSQL_ASSOC);
			}else{
				$instance = new DBTool_DBUtil();
				$sql = "select module_id from tc_user_privilege where user_id='".$user_id."' ;";
				$res=$instance->executeDQL($sql,MYSQL_ASSOC);
				$module_id=$res[0]['module_id']; //用户模块ID
				$module_sql ="select  *  from tc_module where  module_id in (".$module_id.") and module_id !=0 ;"; //用户所在组的菜单
				$module=$instance->executeDQL($module_sql,MYSQL_ASSOC);
			}
			if(empty($module)){
				$instance->close_conn();
				return null;
			}
			else{
				$instance->close_conn();
				return $module;
			}
		}catch(Exception $e){
			$instance->close_conn();
			return null;
		}
		$instance->close_conn();
		return null;

	}
	/**
	 *获取用户拥有模块（除公有模块）
	 * zhoujx
	 * 1.到模块表中查询所有模块
	 * 2.如果结果为空，return null
	 * 3.如果结果不为空，return menu
	 * */
	public function getAllModule(){
		try{
			$instance = new DBTool_DBUtil();
			$module_sql ="select  *  from tc_module ;"; //用户所在组的菜单

			$module=$instance->executeDQL($module_sql,MYSQL_ASSOC);
			if(empty($module)){
				$instance->close_conn();
				return null;
			}
			else{
				$instance->close_conn();
				return $module;
			}
		}catch(Exception $e){
			$instance->close_conn();
			return null;
		}
		$instance->close_conn();
		return null;
	}

	/**
	 * 添加菜单
	 * zhoujx
	 * 1.将接受的菜单组织成sql语句，执行
	 * 2.如果执行成功，将执行数据输出到日志表中  return true
	 * 3.如果执行失败，return false
	 *
	 * */
	public function addMenu($menuarray){
		try{
			$instance = new DBTool_DBUtil();
			$array = array('iissii',$menuarray['module'],$menuarray['selectmenu'],$menuarray['menuname'],$menuarray['menuurl'],$menuarray['menusort'],$menuarray['view']);
			$insertsql = "insert into tc_menu(module_id,menu_pid,menu_name,menu_url,menu_sort_id,menu_view) values(?,?,?,?,?,?)";
			$result_case_v=$instance->executeInsert($insertsql,$array);//获取刚插入tc_menu 记录的id
			if($result_case_v>0){
				$instance->close_conn();
				$log = new Models_Log_Log();
				$log->saveLog("添加ID为".$result_case_v."的菜单");
				return true;
			}
			$instance->close_conn();
			return false;
		}catch(Exception $e){
			$instance->close_conn();
			return false;
		}
		$instance->close_conn();
		return false;

	}

}