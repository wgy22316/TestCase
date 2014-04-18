<?php
class Models_Table_testExp
{
	/**
	*通过case_v_id获取casename、version(未使用预加载)
	* @param unknown $case_v_id  查询案例id、casename、version
	*/
	public function getImfById($case_v_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "select tc_case_v.case_id,version,tc_case.casename from tc_case_v,tc_case where case_v_id = ".$case_v_id." and tc_case_v.case_id = tc_case.case_id";
		$res = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		return $res[0];
	}

	/**
	 * 获取可修改的用例列表(未使用预加载)
	 * @param unknown $search  模糊查询的参数
	 * @param unknown $start     分页 开始
	 * @param unknown $length  分页长度
	 * @return 返回数组
	 */
	public function listCaseCanEdit($search,$start,$length,$case_v_id){
		$dbUtil=new DBTool_DBUtil();
		$sql="select tc_id,title,Initial_condition,procedures,expected_result from tc_testcase where case_v_id=".$case_v_id;
		
		if($search!=""){
			$sql.=" and (title like '%".$search."%' or Initial_condition like '%".$search."%' or procedures like '%".$search."%' or expected_result like '%".$search."%')";
		}
		$sql.=" order by tc_id asc";
		if($length!="-1"){
			$sql.=" limit ".intval($start).",".intval($length);
		}

		file_put_contents("SQL.log", $sql);
		$res=$dbUtil->executeDQL($sql,MYSQLI_NUM);
		return $res;
	}

	/**
	 * 添加记录
	 * @param unknown $arr   表单数据
	 * @return Ambigous <1:成功<br>, number>
	 */
	public function addTest($arr){
		$dbUtil=new DBTool_DBUtil();
		// if(!$this->updateVersion($dbUtil,$arr[0],"添加用例"))
		// 	return 3;
		$sql = "select max(tc_id) from tc_testcase where case_v_id = ".$arr[0];
		$tc_id = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		if($tc_id)
			$tcId=$tc_id[0][0]+1;
		else
			$tcId=1;
		$sql="insert into tc_testcase (case_v_id,tc_id,title,Initial_condition,procedures,expected_result) values(?,".$tcId.",?,?,?,?)";
		array_unshift($arr, 'issss');
		$res=$dbUtil->executeSTMT($sql, $arr);
		return $res;
	}
	
	/**
	 * 更改用例
	 * @param unknown $arr
	 * @return Ambigous <1:成功<br>, number>
	 */
	public function updateTest($arr){
		$dbUtil = new DBTool_DBUtil();
		$sql="update tc_testcase set title=?, Initial_condition=?,procedures=?,expected_result=? where tc_id=? and case_v_id=?;";
		array_unshift($arr, 'ssssii');
		$res=$dbUtil->executeSTMT($sql, $arr);
		return $res;
	}
	/**
	 * 删除用例(未使用预加载)
	 * @param unknown $tc_id
	 * @param unknown $case_v_id
	 * @return Ambigous <1:成功<br>, number>
	 */
	public function deleteTest($tc_ids, $case_v_id){
		$dbUtil = new DBTool_DBUtil();
		$tc_id = implode(",", $tc_ids);
		$sql="delete from tc_testcase where tc_id in (".$tc_id.") and case_v_id=".$case_v_id;
		// $sql="delete from tc_testcase where tc_id in (?) and case_v_id=?";
		// file_put_contents("SQL.log", "删除1 ".$sql."\r\n",FILE_APPEND);
		$res=$dbUtil->executeDML($sql);
		// $res=$dbUtil->executeDML($sql);
		return $res;
		
	}
	
	/**
	 *  获取用例条数(未使用预加载)
	 * @return Ambigous <返回关联数组, multitype:unknown >
	 */
	public function getTotal($search,$case_v_id){
		$dbUtil=new DBTool_DBUtil();
		$sql="select count(tc_id) from tc_testcase where case_v_id='".$case_v_id."'";
		if($search!=""){
			$sql.=" and (title like '%".$search."%' or Initial_condition like '%".$search."%' or procedures like '%".$search."%' and expected_result like '%".$search."%')";
		}
		return $dbUtil->executeDQL($sql, MYSQL_NUM);
	}
}