<?php

class HomeController extends Yaf_Controller_Abstract{
	

	//默认action
	public function homeAction(){

		$this->getView();
	}
	/**
	 * ajax获取用户名
	 * zhoujx
	 * 从session中获取用户名username
	 */
	public function getuserAction(){

		if($this->getRequest()->isXmlHttpRequest()){
			
			$menu = new Models_User_Menu();
			$username = Yaf_Session::getInstance()->get('username');
			if($username==null||$username==''){//检查session是否存在username，以确定用户是否登陆
				$array = array("state"=>"unlogin","error"=>"你还未登录，请返回登录");
			}
			else{
				$array = array("state"=>"success","username"=>$username);
			}
			echo json_encode($array);
		}
		return false;
	}
	/**
	 * ajax获取菜单（前置条件：user_id存在于session中）
	 * zhoujx
	 * 1.判断session中是否已经存在已经按照父子菜单结构化后的menuarray属性
	 * 	如果有，取出后输出
	 * 	如果没有，
	 * 		1.判断session中是否有priviarray属性
	 * 			如果有，取出priviarray
	 * 			如果没有，通过user_id到数据库查询用户的权限菜单
	 * 		2.格式化priviarray
	 * 		3.输出到页面
	 */
	public function menuAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			if(Yaf_Session::getInstance()->has('menuarray')){
				$arraynode= Yaf_Session::getInstance()->get('menuarray');
				$array = array("state"=>"success","menu"=>$arraynode);
			}else{
				if(Yaf_Session::getInstance()->has('priviarray')){

				}else{
					$user_id = Yaf_Session::getInstance()->get('user_id');
					$menu = new Models_User_Menu();
					$getmenuarray =  $menu->findMenu($user_id);//查询user_id用户的权限
					Yaf_Session::getInstance()->set('priviarray',$getmenuarray);//将个人权限菜单放入session
				}
				$priviarray = Yaf_Session::getInstance()->get('priviarray');
				$menuarray = $this->getMenu($priviarray);
				if($menuarray==null||$menuarray==''){
					$array = array("state"=>"fail","error"=>"你没有任何可操作的内容");
				}else{
					$arraynode = $this->generateTree($menuarray);//创建菜单的生成树
					Yaf_Session::getInstance()->set('menuarray',$arraynode);
					$array = array("state"=>"success","menu"=>$arraynode);
				}

			}
			echo json_encode($array);
		}
		return false;
	}
	/**
	 * 获取父菜单下拉框内容（前置条件：user_id存在于session中）
	 * zhoujx
	 * 	1.判断session中是否有priviarray属性
	 * 		如果有，取出priviarray
	 * 		如果没有，通过user_id到数据库查询用户的权限菜单
	 * 	2.通过priviarray生成一个以菜单id为下标，菜单名为对应值得数组
	 * 	3.输出到页面
	 */
	public function getparentmenuAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			if(Yaf_Session::getInstance()->has('priviarray')){
				$parentmenu= Yaf_Session::getInstance()->get('priviarray');
			}
			else{
				$user_id = Yaf_Session::getInstance()->get('user_id');
				$menu = new Models_User_Menu();
				$parentmenu =  $menu->findMenu($user_id);//查询user_id用户的权限
				Yaf_Session::getInstance()->set('priviarray',$parentmenu);//将个人权限菜单放入session
			}
			if($parentmenu==null||$parentmenu==''){
					$array = array("state"=>"fail","error"=>"获取父菜单下拉框内容失败");
			}else{
				$formatparentmenu = $this->formatparentmenu($parentmenu);
				$array = array("state"=>"success","parentmenu"=>$formatparentmenu);
			}
			echo json_encode($array);
		}
		return false;
	}

	//

	/**
	 * 格式化父菜单（前置条件：$parentmenu中存在menu_id\menu_view\menu_name属性）
	 * zhoujx
	 * 	1.判断$parentmenu中menu_view==1
	 * 		如果不等于1，说明改菜单没有视图，不能当做父节点
	 * 		如果等于1
	 * 			1.将menu_id、menu_name组成一个数值返回
	 */
	public function formatparentmenu($parentmenu){
		$array = array();
		for($i=0,$length=sizeof($parentmenu);$i<$length;$i++){
		 	if($parentmenu[$i]['menu_view']==1){
				$array[] = array("key"=>$parentmenu[$i]['menu_id'],"value"=>$parentmenu[$i]['menu_name']);
			}
		}
		return $array;
	}
	/**
	 * 修改用户当前模块环境（前置条件：模块数组存在于session中）
	 * zhoujx
	 * 	1.通过前端传回的modele_name到module匹配，找到对应的game_id
	 * 	2.将找到的game_id放入session中
	 */
	public function changemoduleAction(){
		if($this->getRequest()->isXmlHttpRequest()){
	   		$module_name = $this->getRequest()->getPost('module');	
			$module = Yaf_Session::getInstance()->get('module');
			$game_id = $this->getmoduleIdByname($module,$module_name);
			Yaf_Session::getInstance()->set('game_id',$game_id);
			$game_id = Yaf_Session::getInstance()->get('game_id');
			$array = array("state"=>"success");
			echo json_encode($array);
		}
		return false;
	}
	/**
	 * 根据模板名称获取模板id（前置条件：$module为模块数组、$name为游戏名）
	 * zhoujx
	 * 	1.通过前端传回的modele_name到module匹配，找到对应的module_id
	 * 	2.将找到的module_id返回
	 */
	public function getmoduleIdByname($module,$name){
		for($i=0,$length=sizeof($module);$i<$length;$i++){
			if($module[$i]['module_name']==$name){
				return $module[$i]['module_id'];
			}
		}
	}
	/**
	 * 获取模板（前置条件：user_id存在于session中）
	 * zhoujx
	 * 1.判断session中是否已经存在游戏game_id属性
	 * 	如果有，取出返回
	 * 	如果没有，取出module
	 * 		1.判断session中是否已经存在模块module属性
	 * 			如果有，取出第一个module_id放入session中，作为默认选中的模块
	 * 			如果没有，通过session中的user_id到数据查询module
	 * 				1.查询结果为空，说明该用户没有任何可以操作的页面，return  fail
	 * 				2.结果不为空，将module、module第一个module_id放入session中
	 */
	public function getmoduleAction(){
		if($this->getRequest()->isXmlHttpRequest()){
			if(Yaf_Session::getInstance()->has('game_id')){////检查session是否存在module
				$game_id = Yaf_Session::getInstance()->get('game_id');
				$module = Yaf_Session::getInstance()->get('module');
				$array = array("state"=>"success","module"=>$module,"game_id"=>$game_id);
			}else{
				if(Yaf_Session::getInstance()->has('module')){////检查session是否存在module
					$module = Yaf_Session::getInstance()->get('module');
					$game_id = $module[0]['module_id'];
					$array = array("state"=>"success","module"=>$module,"game_id"=>$game_id);
				}else{
					$menu = new Models_User_Menu();
					$user_id = Yaf_Session::getInstance()->get('user_id');
					$module = $menu->getModule($user_id);//获取权限模块名
					if($module==null||$module==''){
						$array = array("state"=>"fail","error"=>"你没有可以操作的模块，如需要请找管理员分配权限");
					}
					else{
						Yaf_Session::getInstance()->set('module',$module);//将个人module放入session
						$game_id = $module[0]['module_id'];
						Yaf_Session::getInstance()->set('game_id',$game_id);//放入默认的模块名
						$array = array("state"=>"success","module"=>$module,"game_id"=>$game_id);
					}
				}
			}
			echo json_encode($array);
		}
		return false;
	}

	/**
	 * 获取有视图的菜单（前置条件：$menu中存在menu_view属性）
	 * zhoujx
	 * 1.for循环判断数组中的元素的menu_view是否等
	 * 	如果有，取出返回
	 * 	如果没有，取出module
	 * 		1.判断session中是否已经存在模块module属性
	 * 			如果有，取出第一个module_id放入session中，作为默认选中的模块
	 * 			如果没有，通过session中的user_id到数据查询module
	 * 				1.查询结果为空，说明该用户没有任何可以操作的页面，return  fail
	 * 				2.结果不为空，将module、module第一个module_id放入session中
	 */
	public function getMenu($menu){
		$menuarray = array();
		 for($i=0,$length=sizeof($menu);$i<$length;$i++){
		 	if($menu[$i]['menu_view']==1){
		 		array_push($menuarray,$menu[$i]);
		 	}
		 }
		return $menuarray;
	}
	/**
	 * 组织菜单tree（前置条件：$menu中存在menu_id、menu_pid属性）
	 * zhoujx
	 * 1.获取menu数组中最大的menu_id、循环将数组下标i元素赋值为menu_id==i的元素
	 * 2.for循环判断当前元素是否有子元素
	 * 	如果有，把子节点添加到当前元素的son元素中、在把父节点添加要item数组中
	 * 	如果没有，把该节点添加到item中
	 * 3.将item中为空的元素下标重建
	 * 
	 */
	function generateTree($menuarray){
		 $tree = array();
		 $items = array();
		 $length=$this->maxmenu_id($menuarray);
		 for($i=0,$length;$i<$length;$i++){
		 	$items[$i+1]=$this->menuindex($i+1,$menuarray);
		 }
		 foreach($items as $item){
			 if(isset($items[$item['menu_pid']])){//判断items节点是否有子节点
				 $items[$item['menu_pid']]['son'][] = &$items[$item['menu_id']];//把子节点加入本父节点中
			 }else{
				 $tree[] = &$items[$item['menu_id']];
			 }
		 }
		for($i=0,$length=sizeof($tree);$i<$length;$i++){
			if($tree[$i]['menu_id']==null)
				unset($tree[$i]);
		}
		$tree = array_values($tree);
		return $tree;
	}
	/**
	 * 找出最大的menu_id（前置条件：$menu中存在menu_id属性）
	 * zhoujx
	 * 1.循环获取menu数组中最大的menu_id
	 */
	function maxmenu_id($menuarray){
		$max=0;
		for($i=0,$length=sizeof($menuarray);$i<$length;$i++){
			if($max<$menuarray[$i]['menu_id'])
				$max=$menuarray[$i]['menu_id'];
		 }
		 return $max;
	}
	/**
	 * 将数组下标和menu_id相同（前置条件：$menu中存在menu_id属性）
	 * zhoujx
	 * 1.循环获获得与index相同的menu_id元素
	 */
	function menuindex($index,$menuarray){
		for($i=0,$length=sizeof($menuarray);$i<$length;$i++){
			if($index==$menuarray[$i]['menu_id'])
				return $menuarray[$i];
		 }
	}
	/**
	 * 将数组下标和menu_id相同（前置条件：$menu中存在menu_id属性）
	 * zhoujx
	 * 删除所有session中的值
	 */
	public function loginoutAction(){
		if($this->getRequest()->isXmlHttpRequest()){
				Yaf_Session::getInstance()->del('username');//username移除session
				Yaf_Session::getInstance()->del('user_id');//用户id移除session
				Yaf_Session::getInstance()->del('menuarray');//用户菜单移除session
				Yaf_Session::getInstance()->del('priviarray');//用户拥有的菜单和ajax请求总和session
				Yaf_Session::getInstance()->del('allprivi');//系统所有权限url
				Yaf_Session::getInstance()->del('privi');//用户拥有权限url移除session
				Yaf_Session::getInstance()->del('game_id');//当前游戏id移除session
				Yaf_Session::getInstance()->del('module');//模块移除session
			$array = array("state"=>"success");
			echo json_encode($array);
		}
		return false;
	}

	//跳转到菜单排序的页面
	public function allocamenuAction(){
		$this->getView();
	}

	//跳转到菜单排序的页面
	public function allocamenu1Action(){
		$this->getView();
	}
	/**
	 *ajax获取菜单（前置条件：session中存在priviarray属性）
	 * zhoujx
	 * 1.通过sessiong中获取用户权限菜单
	 * 2.获取有所模块
	 */
	public function sortmenu1Action(){

		if($this->getRequest()->isXmlHttpRequest()){
			$menu = new Models_User_Menu();
			$menuarray = Yaf_Session::getInstance()->get('priviarray');
			$menu = new Models_User_Menu();
			$module = $menu->getAllModule();//获取所有模块
			if($menuarray!=null){
				$array = array("state"=>"success","menu"=>$menuarray,"module"=>$module);
			}
			else{
				$array = array("state"=>"fail","error"=>"获取菜单失败");
			}
			echo json_encode($array);
		}
		return false;
	}
	/**
	 *ajax更新菜单
	 * zhoujx
	 * 1.通过前端传回的menu数组
	 * 2.到数据库更新菜单
	 * 	更新成功return seccess
	 * 	更新失败return  fail
	 */
	public function updatemenuAction(){
		if($this->getRequest()->isXmlHttpRequest()){
	   		$menuarray = $this->getRequest()->getPost('menu');
					$menu = new Models_User_Menu();
					$isupdate = $menu->updatemenu($menuarray);
					if($isupdate){
			// 			// $this-> menuAction();
						$array = array("state"=>"success");
					}else{
						$array = array("state"=>"fail","error"=>"更新失败，请检查你输入的内容是否正确");
					}
			// 	}
	  //  		}
			echo json_encode($array);
		}
		return false;
	}
	/**
	 *ajax删除菜单
	 * zhoujx
	 * 1.通过前端传回的menu_id
	 * 2.到数据库删除
	 * 	更新成功return seccess
	 * 	更新失败return  fail
	 */
	public function deletemenuAction(){
		if($this->getRequest()->isXmlHttpRequest()){
	   		$id = $this->getRequest()->getPost('id');
			$menu = new Models_User_Menu();
			$isdelete = $menu->deletemenu($id);
			if($isdelete){
				$array = array("state"=>"success");
			}else{
				$array = array("state"=>"fail","error"=>"删除失败");
			}
			echo json_encode($array);
		}
		return false;
	}


	/**
	 *ajax删除菜单
	 * zhoujx
	 * 1.通过前端传回的menu
	 * 2.判断数组的合法性
	 * 3.将menu添加到数据库
	 * 	添加成功return seccess
	 * 	添加失败return  fail
	 */
	public function addmenuAction(){
		if($this->getRequest()->isXmlHttpRequest()){
	   		$menuarray = $this->getRequest()->getPost('menu');	
	   		// print_r($menuarray);
	   		if($menuarray['module']==null||$menuarray['module']==''){
				$array = array("state"=>"fail","error"=>"菜单所属模块不能为空");

	   		}else if($menuarray['selectmenu']==null||$menuarray['selectmenu']==''){
				$array = array("state"=>"fail","error"=>"父节点不能为空");

	   		}else if($menuarray['menuname']==null||$menuarray['menuname']==''){
				$array = array("state"=>"fail","error"=>"菜单名不能为空");

	   		}else if($menuarray['menuurl']==null||$menuarray['menuurl']==''){
				$array = array("state"=>"fail","error"=>"菜单链接不能为空");

	   		}else if($menuarray['menusort']==null||$menuarray['menusort']==''){
				$array = array("state"=>"fail","error"=>"菜单显示序号不能为空");

	   		}else if($menuarray['view']==null||$menuarray['view']==''){
				$array = array("state"=>"fail","error"=>"视图不能为空");
	   		}else{
	   			$menu = new Models_User_Menu();
	   			if($menuarray['module']!='0'){
	   				$game_id = Yaf_Session::getInstance()->get('game_id');
	   				$menuarray['module'] = $game_id;//如果不属于当前模块，则属于当前所选游戏模块
	   			}
				$isadd = $menu->addMenu($menuarray);
				if($isadd){
					$array = array("state"=>"success");
				}else{
					$array = array("state"=>"fail","error"=>"添加失败，请再次提交");
				}
	   		}

			echo json_encode($array);
		}
		return false;

	}


}

