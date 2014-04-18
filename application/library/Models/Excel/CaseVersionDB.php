<?php
class Models_Excel_CaseVersionDB{
	/**
	 * 添加，获取自增case_v_id
	 * @param unknown_type $filename
	 */
	public function addCaseVersion($data){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		$sql = "insert into tc_case_v(case_id , version,author_id,create_time) values(?,?,?,?)";
 		$mysqli_stmt = $msqli->prepare($sql);
 		$mysqli_stmt->bind_param("isis",$data['case_id'],$data['version'],$data['author_id'],$data['create_time']);
 		$msqli->query("set names utf8");
 		$mysqli_stmt->execute();
 		$case_v_id = mysqli_insert_id($msqli); //返回本次插入的自增ID
		//释放结果
		$mysqli_stmt->free_result();
		//关闭预编译语句
		$mysqli_stmt->close();
		//关闭连接
		$msqli->close();
		return $case_v_id;
	}
	
	/**
	 * 通过case_id查找所有case下的case版本
	 * @param unknown_type $caseId
	 */
	public function getCaseVersionByCaseId($caseId){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		$sql = "select *  from tc_case_v where case_id = ?";
		$params = array("i",$caseId);
		$data = $db->query($sql, $params);
		return $data;
	}
	
	/**
	 * 判断当前游戏版本下，该用户是否存在一个任务，且有未提交的更新
	 * @param unknown_type $caseId
	 * @param unknown_type $author_id
	 * @param unknown_type $game_v_id
	 */
	public function isExistCaseVersion($caseId,$author_id,$game_v_id,$task_id,$is_commit){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		$sql = "select *  from tc_case_v where case_id = ? and author_id = ? and game_v_id = ? and task_id = ? and is_commit =?";
		$params = array("iiiii",$caseId,$author_id,$game_v_id,$task_id,$is_commit);
		$data = $db->query($sql, $params);
		if(empty($data)){
			return false;  //不存在
		}else{
			return true;          //存在
		}
	}
	
	/**
	 * 获取当前游戏版本下，该用户未提交的case
	 * @param unknown_type $caseId
	 * @param unknown_type $author_id
	 * @param unknown_type $game_v_id
	 */
	public function getCaseVersionId($caseId,$author_id,$game_v_id,$is_commit){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		$sql = "select case_v_id from tc_case_v where case_id = ? and author_id = ? and game_v_id =? and is_commit =?";
		$params = array("iiii",$caseId,$author_id,$game_v_id,$is_commit);
		$caseVersion = $db->query($sql, $params);
		return $caseVersion[0]['case_v_id']; 
		
	}
	
	//获取案例的最大版本号
	public function getMaxCaseVersionByCaseId($caseId){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		$sql = "select max(version) from tc_case_v where case_id = ?";
		$params = array("i",$caseId);
		$data = $db->query($sql, $params);
		return $data[0]['max(version)'];
	}
}