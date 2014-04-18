<?php

class TaskController extends Yaf_Controller_Abstract
{

//-------------------------任务指派--------------------------------------------------
	//跳转创建或选择游戏版本页面
	public function cosGameVersionAction()
	{
		
		// $game_id = @$_GET['game_id'];
		// var_dump($game_id);
		// Yaf_Session::getInstance()->set("game_id",$game_id);
		$game_id = Yaf_Session::getInstance()->get("game_id");
		$taskModel = new Models_Task_task();
		$gameVersion = $taskModel->listGameVersions($game_id);
		$gameName = $taskModel->getGameNameByGameId($game_id);
		return $this->getView()->assign(['game_version'=>$gameVersion,'gameName'=>$gameName]);
	}
	
	//跳转指派任务界面
	public function showAllotTasksAction()
	{
		$game_v_id = htmlspecialchars(@$_GET['game_v_id']);
		$game_id = Yaf_Session::getInstance()->get('game_id');
		if(null!=$game_v_id && -1!=$game_v_id)
		{
			$assigner_id = Yaf_Session::getInstance()->get('user_id');
			$taskModel = new Models_Task_task();
			$json = $taskModel->listAllTask($game_v_id,$assigner_id);
			for($i=0;$i<count($json);$i++)
			{
				//$json[$i][] = '<a href="../../case/testcase/showCaseByTask?task_id='.$json[$i][3].'&&task_name='.$json[$i][0].'">查看</a>';
				$json[$i][] = '<a href="" class="review">查看</a>';
				$json[$i][] = "<a class='edit' href=''>Edit</a>";
				$json[$i][] = "<a class='delete' href=''>Delete</a>";
			}
			$json=array("aaData"=>$json);
			//var_dump($json);
		}
		else
			$json = array("aaData"=>array());
		echo json_encode($json);
		return false;
		// return $this->getView()->assign(['json'=>json_encode($json),'game_v_id'=>$game_v_id,'game_id'=>$game_id]);
	} 
	//查询所有测试者
	public function listUsersAction()
	{
		
		$userModel = new Models_User_Usermodel();
		$testers = $userModel->findAllUser();
		echo json_encode($testers);
		return false;
	}
	//查询当前游戏版本下所有任务
	// public function listAllTaskAction()
	// {
	// 	$game_v_id = htmlspecialchars(@$_POST['game_v_id']);
	// 	if(null!=$game_v_id && -1!=$game_v_id)
	// 	{
	// 		$assigner_id = Yaf_Session::getInstance()->get('user_id');
	// 		$taskModel = new Models_Task_task();
	// 		$json = $taskModel->listAllTask($game_v_id,$assigner_id);
	// 		for($i=0;$i<count($json);$i++)
	// 		{
	// 			$json[$i][] = '<a href="../../case/testcase/showCaseByTask?task_id='.$json[$i][3].'&&task_name='.$json[$i][0].'">查看</a>';
	// 			$json[$i][] = "<a class='edit' href=''>Edit</a>";
	// 			$json[$i][] = "<a class='delete' href=''>Delete</a>";
	// 		}
	// 		$json=array("aaData"=>$json);
	// 	}
	// 	else
	// 		$json = array("aaData"=>array(0));
	// 	echo json_encode($json);
	// 	return false;
	// }
	
	//创建游戏版本
	public function createVersionAction()
	{
		
		$gameVersion = @$_POST['gameVersion'];
		$game_id = Yaf_Session::getInstance()->get('game_id');
		// var_dump($gameVersion);
		// var_dump($game_id);
		$taskModel = new Models_Task_task();
		$res = $taskModel->createGameVersion($game_id,$gameVersion);
		if(-1 == $res)
			echo json_encode(array(-1));
		else
			echo json_encode(array($res,$gameVersion));
		return false;
	}
	//保存任务
	public function saveTaskAction()
	{
		
		$task_id = htmlspecialchars(@$_POST['task_id']);
		$task_name = htmlspecialchars(@$_POST['task_name']);
		$task_desc = htmlspecialchars(@$_POST['task_desc']);
		$tester_name = htmlspecialchars(@$_POST['tester_name']);
		$game_v_id = htmlspecialchars(@$_POST['game_v_id']);
		$taskModel = new Models_Task_task();
		// var_dump($task_id);
		if(null == $task_id)
		{
			$assigner_id = Yaf_Session::getInstance()->get('user_id');
			$json = $taskModel->saveTask($task_name,$task_desc,$assigner_id,$tester_name,$game_v_id);
			if($json != -1&&$json != -2 &&$json != -3)
				$json = array('code'=>1,'taskId'=>$json);
			else
				$json = array('code'=>$json);
		}
		else
		{
			$json = $taskModel->editTask($task_id,$task_name,$task_desc,$tester_name,$game_v_id);
			if($json == 1)
				$json = array('code'=>1,'taskId'=>$task_id);
			else
				$json = array('code'=>$json);
		}
		echo json_encode($json);
		return false;
	}
	//删除任务
	public function deleteTaskAction()
	{
		
		$task_id = htmlspecialchars(@$_POST['task_id']);
		if(null != $task_id&&''!=$task_id)
		{
			$taskModel = new Models_Task_task();
			$json = $taskModel->deleteTask($task_id);
			$json = array('code'=>$json);
		}
		else
			$json = array('code'=>0);
		echo json_encode($json);
		return false;
	}

//-----------------------------------任务指派完成---------------------------------

//-----------------------------------自己的任务-------------------------------------

	//跳转查询自己的所有任务页面
	public function showMyAllTasksAction(){
		if(!Yaf_Session::getInstance()->has('game_id'))
			return false;
		// $game_id = $_GET['game_id'];
		// Yaf_Session::getInstance()->set('game_id',$game_id);
	}

	 //查询自己的所有任务
	public function listMyAllTasksAction()
	{
		$iDisplayStart=$_REQUEST['iDisplayStart'];
		$iDisplayLength=$_REQUEST['iDisplayLength'];
		$game_id = Yaf_Session::getInstance()->get('game_id');
		$user_id = Yaf_Session::getInstance()->get("user_id");
		$taskModel = new Models_Task_task();
		$json = $taskModel->listMyAllTasks($user_id,$game_id,$iDisplayStart,$iDisplayLength);
		$Total=$taskModel->getTotalTasks($user_id);
		$Total=$Total[0][0];
		$json=array(
			"iDisplayLength"=>$iDisplayLength,
			"iTotalRecords"=> $Total,//返回的总数据
			"iTotalDisplayRecords"=> $Total,  //过滤后的数据
			"aaData"=>$json,	
		);
			
		echo json_encode($json);
		
		return false;
	}


//-----------------------------------自己的任务完成-----------------------------

}