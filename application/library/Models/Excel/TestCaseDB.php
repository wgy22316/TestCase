<?php
class Models_Excel_TestCaseDB{
	/**
	 * 判断用例是否存在
	 * @param $gameId 游戏ID
	 */
	public function addTestCase($case_v_id,$data){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		//tc_id,tc_testcase_id, title ,Initial_condition,procedures,expected_result,graph,sound,feature,Total_duration,bug_id) values (?,?,?,?,?,?,?,?,?,?,?)";
		//$sql = "insert into tc_testcase(case_v_id,tc_id, title ,Initial_condition,procedures,expected_result,graph,sound,feature,Total_duration,bug_id) values(?,?,?,?,?,?,?,?,?,?,?)";
		$sql = "insert into tc_testcase(case_v_id,tc_id, title ,Initial_condition,procedures,expected_result) values(?,?,?,?,?,?)";
		$params = array("iissss",$case_v_id,$data[0],$data[1],$data[2],$data[3],$data[4]);
		$flag = $db->del_up_inert_data($sql, $params);
		return $flag;
	}
	
	public function getTestCaseByCaseVersionId($caseVersionId){
 		$db = new DBTool_DB();
 		$msqli = $db->getConnt();
 		//tc_id,tc_testcase_id, title ,Initial_condition,procedures,expected_result,graph,sound,feature,Total_duration,bug_id) values (?,?,?,?,?,?,?,?,?,?,?)";
 		$sql = "select tc_id, title ,Initial_condition,procedures,expected_result,graph,sound,feature,Total_duration,bug_id from tc_testcase where case_v_id = ? order by tc_id";
 		$params = array("i",$caseVersionId);
		$data = $db->query($sql, $params);
		
		$testcases = array();
		//转成符合Excel的格式
		for($i = 0 ; $i<count($data);$i++){
			$testcases[$i] = array_values($data[$i]);
		}
		return $testcases;
	}
	
	public function getTestcaseNameByVersionId($caseVersionId){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		//select casename , version from tc_case_v  , tc_case   where  tc_case_v.case_id = tc_case.case_id and tc_case_v.case_id in(select case_id from tc_case_v where case_v_id =3) and tc_case.case_id in (select case_id from tc_case_v where case_v_id =3)
		$sql = "select casename , version from tc_case_v  , tc_case   where  tc_case_v.case_id = tc_case.case_id and case_v_id=?";
		$params = array("i",$caseVersionId);
		$data = $db->query($sql, $params);
		return $data;
	}
	
	public function delTestCaseByCaseVersionId($caseVerionId){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		//tc_id,tc_testcase_id, title ,Initial_condition,procedures,expected_result,graph,sound,feature,Total_duration,bug_id) values (?,?,?,?,?,?,?,?,?,?,?)";
		$sql = "delete from tc_testcase where case_v_id = ?";
		$params = array("i",$caseVerionId);
		$flag = $db->del_up_inert_data($sql, $params);
		return $flag;
	}
}