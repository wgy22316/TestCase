<?php
/**
 * @name SamplePlugin
 * @desc Yaf定义了如下的6个Hook,插件之间的执行顺序是先进先Call
 * @see http://www.php.net/manual/en/class.yaf-plugin-abstract.php
 * @author root
 */
class PrivilegePlugin extends Models_User_Privilege {


	
	/*
	*获取权限，并放入session中
	*allprivi表示系统所有权限  privi是当前用户的权限
	*/ 
	public function getPrivilege(){
		if(Yaf_Session::getInstance()->has('privi')){
		}else{
			$menuarray = Yaf_Session::getInstance()->get('priviarray');//获取用户拥有的菜单和ajax请求总和session
			$privi = array();
			for($i=0,$length=sizeof($menuarray);$i<$length;$i++){
				array_push($privi,$menuarray[$i]['menu_url']);
			}
			Yaf_Session::getInstance()->set('privi',$privi);//用户拥有权限url
		}
		if(Yaf_Session::getInstance()->has('allprivi')){

		}else{
			$menu = new Models_User_Menu();
			$allmenu = $menu->getAllMenu();
			$allprivi = array();
			for($i=0,$length=sizeof($allmenu);$i<$length;$i++){
				array_push($allprivi,$allmenu[$i]['menu_url']);
			}
			Yaf_Session::getInstance()->set('allprivi',$allprivi);//系统所有操作权限
		}
		// print_r(Yaf_Session::getInstance()->get('privi'));
		// print_r(Yaf_Session::getInstance()->get('allprivi'));
	}

	/**
	 * 在路由之前触发，这个是6个事件中, 最早的一个. 但是一些全局自定的工作, 还是应该放在Bootstrap中去完成
	 * @param  Yaf_Request_Abstract  $request  [description]
	 * @param  Yaf_Response_Abstract $response [description]
	 * @return [type]                          [description]
	 */
	public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {

                // printf("---routerStartup");
	}

	/**
	 * [路由结束之后触发，此时路由一定正确完成, 否则这个事件不会触发]
	 * @param  Yaf_Request_Abstract  $request  [description]
	 * @param  Yaf_Response_Abstract $response [description]
	 * @return [type]                          [description]
	 */
	public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
			$str = $request->getModuleName();//获取单签action
			if($str=='Login'){//如果用户在进行登录模块操作
				$action = $request->getActionName();
				if($action=='loginCk'){
					Yaf_Session::getInstance()->del('username');//username移除session
					Yaf_Session::getInstance()->del('user_id');//用户id移除session
					Yaf_Session::getInstance()->del('menuarray');//用户菜单移除session
					Yaf_Session::getInstance()->del('priviarray');//用户拥有的菜单和ajax请求总和session
					Yaf_Session::getInstance()->del('allprivi');//系统所有权限url
					Yaf_Session::getInstance()->del('privi');//用户拥有权限url移除session
					Yaf_Session::getInstance()->del('game_id');//当前游戏id移除session
					Yaf_Session::getInstance()->del('module');//模块移除session
				}
			}else{
				if($this->isLogin('user_id')){//判断是否登陆过，'user_id'为判断的标识session内容
					$this->getPrivilege();

					$privi = Yaf_Session::getInstance()->get('privi');
					$allprivi = Yaf_Session::getInstance()->get('allprivi');
					if($this->hasPrivilege($request,'privi','allprivi')){//将访问的url到权限中比较
						// printf("有权限");
					}else{
						// printf("没有权限");
// 						// $response->setRedirect("/TestCase/public/Login/Login/ytqr");
						header("Location: /TestCase/public/Login/Login/ytqr");
						exit();
					}
				}
				else{
					$response->setRedirect("/TestCase/public/Login/Login/login");
				}
			}
	}

	/**
	 * [分发循环开始之前被触发]
	 * @param  Yaf_Request_Abstract  $request  [description]
	 * @param  Yaf_Response_Abstract $response [description]
	 * @return [type]                          [description]
	 */
	public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
                // printf("---dispatchLoopStartup");
	}

	/**
	 * [分发之前触发    如果在一个请求处理过程中, 发生了forward, 则这个事件会被触发多次]
	 * @param  Yaf_Request_Abstract  $request  [description]
	 * @param  Yaf_Response_Abstract $response [description]
	 * @return [type]                          [description]
	 */
	public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
                // printf("---preDispatch");
	}

	/**
	 * [分发结束之后触发，此时动作已经执行结束, 视图也已经渲染完成. 和preDispatch类似, 此事件也可能触发多次]
	 * @param  Yaf_Request_Abstract  $request  [description]
	 * @param  Yaf_Response_Abstract $response [description]
	 * @return [type]                          [description]
	 */
	public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
                // printf("---postDispatch");
	}

	/**
	 * [分发循环结束之后触发，此时表示所有的业务逻辑都已经运行完成, 但是响应还没有发送]
	 * @param  Yaf_Request_Abstract  $request  [description]
	 * @param  Yaf_Response_Abstract $response [description]
	 * @return [type]                          [description]
	 */
	public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
                // printf("---dispatchLoopShutdown");
	}
}
