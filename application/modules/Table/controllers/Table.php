<?php

class TableController extends Yaf_Controller_Abstract
{
	//跳转查看所有案例中单个案例及对比2个案例页面
	public function showDetailTestCaseAction()
	{
		
		$case_v_id = @$_GET['case_v_id'];
		$tableModel = new Models_Table_testExp();
		$caseImf = $tableModel->getImfById($case_v_id);
		return $this->getView()->assign(["caseImf"=>$caseImf,"case_v_id"=>$case_v_id]);
	}
	//通过case_v_id查询所有案例中单个案例的所有用例数据
	public function listDetailTestCaseAction()
	{	
		$sSearch=htmlspecialchars(@$_REQUEST['sSearch']);
		$sSearch=str_ireplace("'", "", $sSearch);
		$iDisplayStart=htmlspecialchars($_REQUEST['iDisplayStart']);
		$iDisplayLength=htmlspecialchars($_REQUEST['iDisplayLength']);
		$case_v_id=htmlspecialchars($_REQUEST['case_v_id']);
		if(!$sSearch){
			$sSearch="";
		}
		$test=new Models_Table_testExp();
		$json=$test->listCaseCanEdit($sSearch, $iDisplayStart, $iDisplayLength, $case_v_id);//查询数据		
		//在json数据头添加多选框
		foreach ($json as $k=>$v){
			$checkbox="<input type=\"checkbox\" class='chk' name=\"tc_id\" value=\"".$json[$k][0]."\"/>";
			array_unshift($json[$k], $checkbox);
		}
		$Total=$test->getTotal($sSearch,$case_v_id);//查询用例条数
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

	public function compareTwoCaseAction(){
		$firstCaseVersionId = $_GET['firstCase'];
		$secondCaseVersonId = $_GET['secondCase'];
		return $this->getView()->assign(array('firstCaseVersionId'=>$firstCaseVersionId,'secondCaseVersonId'=>$secondCaseVersonId));
	}
	
	/**
	 * 更新用例信息
	 */
	public function updateAction()
	{
		
		$tc_id=htmlspecialchars(@$_POST["tc_id"]);
		$case_v_id=htmlspecialchars(@$_POST["case_v_id"]);
		$title=htmlspecialchars(@$_POST["title"]);
		$initial_condition=htmlspecialchars(@$_POST["initial_condition"]);
		$procedure=htmlspecialchars(@$_POST["procedure"]);
		$expected_result=htmlspecialchars(@$_POST["expected_result"]);		
		$test=array($title,$initial_condition,$procedure,$expected_result,$tc_id,$case_v_id);
		$updateModel = new Models_Table_testExp();
		$res=$updateModel->updateTest($test);
		echo json_encode(array("code"=>$res));
		return false;
	}
	
	/**
	 * 删除用例
	 * @param tc_id数组 $tc_ids  要删除的用例id数组
	 */
	public function deleteAction()
	{
		
		$tc_ids=@$_POST['ids'];
		$case_v_id=@$_POST['case_v_id'];
		// print_r($tc_ids);
		// print_r($case_v_id);
		if(count($tc_ids)&&$case_v_id)
		{
			$deleteModel=new Models_Table_testExp();
			$json = array('code'=> $deleteModel->deleteTest($tc_ids, $case_v_id));
		}else{
			$json = array("code"=>3);//tc_id为空或case_v_id为空
		}
		echo json_encode($json);
		return false;
	}
	
	/**
	 * 添加用例
	 */
	public function addAction()
	{	
		$case_v_id=htmlspecialchars(@$_POST['case_v_id']);
		$title=htmlspecialchars(@$_POST['title']);
		$initial_condition=htmlspecialchars(@$_POST['initial_condition']);
		$procedure=htmlspecialchars(@$_POST['procedure']);
		$expected_result=htmlspecialchars(@$_POST['expected_result']);
		//$author_id = $_SESSION['user_id'];
		$create_time = date('Y-m-d G:i:s');
		
		if(''!=$title&&null!=$title&&''!=$expected_result&&null != $expected_result){
		
			$test=array($case_v_id,$title,$initial_condition,$procedure,$expected_result);
			$addModel=new Models_Table_testExp();
			$res=$addModel->addTest($test);
			echo json_encode(array("code"=>$res));
		}else{
			echo json_encode(array("code"=>4));//title或期望结果字段存在空
		}
		return false;
	}
}