<?php

class Models_Task_task
{
	public function getGameVersionByGameId($game_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "select game_v from tc_game_v where game_id = ".$game_id;
		return $dbUtil->executeDQL($sql,MYSQLI_NUM);
	}

	public function getGameVersionByTaskId($task_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "select game_v_id from tc_task where task_id = ".$task_id;
		$res = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		return $res[0][0];
	}

	public function getGameNameByGameId($game_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "select gamename from tc_game where game_id = ".$game_id;
		$res = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		return $res[0][0];
	}

	public function getModulesByGameId($game_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "select modules_id,modulesname from tc_game_modules where game_id=".$game_id;
		$res = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		$arr = [];
		for($i = 0; $i<count($res);$i++)
		{
			$arr[] = array('id'=>$res[$i][0],'text'=>$res[$i][1]);
		}
		// var_dump($arr);
		return $arr;
	}

	public function getTaskNameByTaskId($task_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "select task_name from tc_task where task_id=".$task_id;
		$res = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		return $res[0][0];
	}

	public function listAllTask($game_v_id,$assigner_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "SELECT tct.task_name,tct.task_desc,tc_userinfo.username,tct.task_id
			from tc_task as tct
			LEFT JOIN tc_userinfo on tct.tester_id = tc_userinfo.user_id
			where tct.game_v_id = ".$game_v_id." and tct.assigner_id=".$assigner_id." order by tct.tasktime desc";
		return $dbUtil->executeDQL($sql,MYSQLI_NUM);
	}

	public function createGameVersion($game_id,$game_v)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "select game_v_id from tc_game_v where game_v = '".$game_v."' and game_id=".$game_id;
		$res = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		if(0 == count($res))
		{
			$sql = "insert into tc_game_v (game_v,game_id) value(?,?)";
			return $dbUtil->executeInsert($sql,array('si',$game_v,$game_id));
		}
		else
			return -1;
	}

	public function listGameVersions($game_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "SELECT game_v_id,game_v from tc_game_v where game_id=".$game_id." order by game_v desc";
		return $dbUtil->executeDQL($sql,MYSQLI_NUM);
	}

	public function saveTask($task_name,$task_desc,$assigner_id,$tester_name,$game_v_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$date = date('Y-n-d H:i:s');
		$sql = "select user_id from tc_userinfo where username = '".$tester_name."'";
		//var_dump($sql);
		$tester_id = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		$tester_id = $tester_id[0][0];
		$sql = "insert into tc_task (task_name,task_desc,assigner_id,tester_id,game_v_id,tasktime,iscomplet) value(?,?,?,?,?,?,?)";
		$data = array('ssssisi',$task_name,$task_desc,$assigner_id,$tester_id,$game_v_id,$date,0);
		return $dbUtil->executeInsert($sql,$data);
	}

	public function editTask($task_id,$task_name,$task_desc,$tester_name,$game_v_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "select user_id from tc_userinfo where username = '".$tester_name."'";
		//var_dump($sql);
		$tester_id = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		$tester_id = $tester_id[0][0];
		$sql = "update tc_task set task_name=?,task_desc=?,tester_id=? where task_id=?";
		$data = array('sssi',$task_name,$task_desc,$tester_id,$task_id);
		return $dbUtil->executeSTMT($sql,$data);
	}

	public function deleteTask($task_id)
	{
		$dbUtil = new DBTool_DBUtil();

		$sql = "delete from tc_task where task_id = ".$task_id;
		return $dbUtil->executeDML($sql);
	}

	public function listMyAllTasks($user_id,$game_id,$start,$length)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "SELECT tct.task_id, tct.task_name, tct.task_desc,concat(tc_game.gamename,tc_game_v.game_v) as '游戏及版本',count(tc_case_v.case_v_id),
				tc_userinfo.username,tct.tasktime,tct.iscomplet
				from tc_task as tct
				LEFT JOIN tc_game_v on tct.game_v_id = tc_game_v.game_v_id
				LEFT JOIN tc_game on tc_game_v.game_id = tc_game.game_id
				LEFT JOIN tc_case_v on tc_case_v.task_id = tct.task_id
				LEFT JOIN tc_userinfo on tct.assigner_id = tc_userinfo.user_id
				where tct.tester_id = '".$user_id."' and tc_game.game_id = ".$game_id."
				GROUP BY tct.task_id
				ORDER BY tct.tasktime desc";
		if($length!="-1"){
			$sql.=" limit ".intval($start).",".intval($length);
		}
		return $dbUtil->executeDQL($sql,MYSQLI_NUM);	

	}

	public function getTotalTasks($user_id)
	{
		$dbUtil = new DBTool_DBUtil();
		return $dbUtil->executeDQL("select count(task_id) from tc_task where tester_id = ".$user_id, MYSQLI_NUM);
	}
}