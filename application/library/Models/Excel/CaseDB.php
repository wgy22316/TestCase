<?php
class Models_Excel_CaseDB{
	/**
	 * 判断用例是否存在
	 * @param $gameId 游戏ID
	 */
	public function isExsitcase($gameId,$casename){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		$sql = "select count(*) from tc_case where game_id = ? and casename=?";
		$params = array("is",$gameId,$casename);
		$data = $db->query($sql, $params);
		if($data[0]['count(*)']== 0){
			return false;
		}else{
			return true;
		}
	}
	/**
	 * 获取caseId
	 * @param unknown_type $gameId
	 * @param unknown_type $casename
	 * @return boolean
	 */
	public function getCaseIdByGameAndCasename($gameId,$casename){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		$sql = "select *  from tc_case where game_id = ? and casename=?";
		$params = array("is",$gameId,$casename);
		$data = $db->query($sql, $params);
		return $data[0]['case_id'];
	}
	
	/**
	 * 获取自增case_id
	 * @param unknown_type $filename
	 */
	public function addCase($caseName , $gameId){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		$sql = "insert into tc_case(casename , game_id) values(?,?)";
 		$mysqli_stmt = $msqli->prepare($sql);
 		$mysqli_stmt->bind_param("si",$caseName,$gameId);
 		$msqli->query("set names utf8");
 		$mysqli_stmt->execute();
 		$id = mysqli_insert_id($msqli); //返回本次插入的自增ID
 		
 		//添加日志
 		$logModel = new Models_Log_Log();
 		$user_id = Yaf_Session::getInstance()->get("user_id");
 		$description = $user_id."添加了".$caseName."用例";
 		$logModel->saveLog($description);
		//释放结果
		$mysqli_stmt->free_result();
		//关闭预编译语句
		$mysqli_stmt->close();
		//关闭连接
		$msqli->close();
		return $id;
	}
	
	public function getAllCaseByGameId($gameId){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		$sql = "select *  from tc_case where game_id = ?";
		$params = array("i",$gameId);
		$data = $db->query($sql, $params);
		return $data;
	}
}