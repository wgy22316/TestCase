<?php
class Models_Log_Log{
	//添加日志
	public static function saveLog($description){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		$user_id = Yaf_Session::getInstance()->get("user_id");
		$logTime = date('Y-m-d H:i:s'); 
		$sql = "insert into tc_log(user_id , description , time) values(?,?,?)";
		$params = array("iss",$user_id,$description,$logTime);
		$result = $db->del_up_inert_data($sql, $params);
		//file_put_contents(APP_PATH."/log/tc_log.log", $user_id."-----".$description."-----".$logTime."\r\n", FILE_APPEND);
		return $result;
	}
	
	//查看日志
	public function queryLog(){
		$db = new DBTool_DB();
 		$msqli = $db->getConnt();
		//$sql = "select user_id , description ,time from tc_log where user_id =".$user_id;
 		$sql = "select user_id , description ,time from tc_log ";
		$msqli->query("set names utf8");
		$rst = $msqli->query($sql);
		$data = array();
		while($row=mysqli_fetch_array($rst,MYSQLI_NUM)){
			$data[] = $row;
				
		}
		$msqli->close();
		return $data;
	}
	
	public function queryLogBytime($fromTime , $toTime){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		$sql = "select user_id , description ,time from tc_log where time between  '".$fromTime."'  and  '".$toTime."'";
		$msqli->query("set names utf8");
		$rst = $msqli->query($sql);
		$data = array();
		while($row=mysqli_fetch_array($rst,MYSQLI_NUM)){
			$data[] = $row;
		
		}
		$msqli->close();
		return $data;
	}
}