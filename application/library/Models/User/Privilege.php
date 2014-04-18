<?php
/**
 * @name SamplePlugin
 * @desc Yaf定义了如下的6个Hook,插件之间的执行顺序是先进先Call
 * @see http://www.php.net/manual/en/class.yaf-plugin-abstract.php
 * @author root
 */
class Models_User_Privilege extends Yaf_Plugin_Abstract {
	

	public function isLogin($user_id){
		if(Yaf_Session::getInstance()->has($user_id)){
			return true;
		}
		return false;
	}

	//参数1表示请求；参数2表示当前用户权限，参数3表示系统所有的权限
	public function hasPrivilege($request,$pri,$allprivi){
		$pri = Yaf_Session::getInstance()->get($pri);		
		$allprivi = Yaf_Session::getInstance()->get($allprivi);	
		$req_pri='../../'.$request->getModuleName().'/'.$request->getControllerName().'/'.$request->getActionName();
		 //print_r($pri);
		// print_r($allprivi);
		// print_r($req_pri);
		if(!array_search($req_pri,$allprivi)){
			// print_r("1");
			return true;
		}else{
			if(array_search($req_pri,$pri)){
			// print_r("2");
				return true;
			}
			else{
			// print_r("3");
				return false;
			}
		}

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
			
			// Yaf_Session::getInstance()->del('username');//username移除session
			// Yaf_Session::getInstance()->del('user_id');//username移除session
			// Yaf_Session::getInstance()->del('menuarray');//用户菜单移除session
			// Yaf_Session::getInstance()->del('priviarray');//用户拥有权限url移除session
			// Yaf_Session::getInstance()->del('game_id');//用户拥有的菜单和ajax请求总和移除session
			// Yaf_Session::getInstance()->del('module');//用户拥有的菜单和ajax请求总和移除session
				if((($user_id==null||$user_id=="")||($username==null||$username==""))){
					$response->setRedirect("/TestCase/public/Login/Login/login");
				}
				else{
					$resAction = $request->getActionName();//获取当前操作
					$privilege = Yaf_Session::getInstance()->get('privilege');//获取当前用户的权限
					$req_pri='../../'.$request->getModuleName().'/'.$request->getControllerName().'/'.$request->getActionName();
					// printf($req_pri);
					// print_r($privilege);
					if(array_search($req_pri,$privilege)){//查看当前操作是否属于当前用户所拥有的权限
						// printf("!!yuequan");
					}
					else{
						// printf("yuequan");
						// $response->setRedirect("/TestCase/public/Login/Login/login");
						// $response->setRedirect("/TestCase/public/Home/Home/home");//越权操作直接转发到首页
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
