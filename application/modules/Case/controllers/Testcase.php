<?php
	class TestCaseController extends Yaf_Controller_Abstract{

//-----------------------v2 start----------------------------------------------

		/**
		*显示所有案例的所有版本
		*/
		public function showAllVersionOfCaseAction()
		{
			$game_id = Yaf_Session::getInstance()->get("game_id");
			$caseModel = new Models_Case_Case();
			$game_name = $caseModel->getGameNamesById($game_id);
			return $this->getView()->assign(["game_id"=>$game_id,"game_name"=>$game_name]);
		}

		/**
		*查询案例的名字、版本号、所属游戏的版本号、创建者、创建时间、提交时间、用例条数
		*
		*/
		public function listAllVersionOfCaseAction()
		{
			$search = @$_GET['search'];
			$search = trim($search);
			$search=str_ireplace("'", "", $search);
			$isreSearch = @$_GET['isreSearch'];
			$game_id = Yaf_Session::getInstance()->get("game_id");
			$iDisplayStart=$_REQUEST['iDisplayStart'];
			$iDisplayLength=$_REQUEST['iDisplayLength'];
			
			if(!$search){
				$search="";
			}
			// echo $search;
			$caseModel = new Models_Case_Case();
			if(0 == $isreSearch)
			{
				$json = $caseModel->getAllCases($iDisplayStart,$iDisplayLength,$game_id);
				// print_r($json);
				for($i = 0 ; $i < count($json) ; $i++){
					$json[$i][] = '<input type="checkbox" name ="caseVersions" class="checkboxes" value="'.$json[$i][0].'">';  //往数组尾插入元素
				}
				// print_r($json);
				$Total=$caseModel->getTotal();
			}
			else if(1 == $isreSearch)
			{
				$json = $caseModel->getAllVersionCasesImf($search,$iDisplayStart,$iDisplayLength,$game_id);
				// print_r($json);
				for($i = 0 ; $i < count($json) ; $i++){
					$json[$i][] = '<input type="checkbox" name ="caseVersions" class="checkboxes" value="'.$json[$i][0].'">';  //往数组尾插入元素
				}
				// print_r($json);
				$Total=$caseModel->getTotals($search,$game_id);
			}
			else
			{
				
				$contentArrs = @$_GET['contentArrs'];
				$author = @$_GET['author'];
				$json = $caseModel->getResearchResult($search,$contentArrs,$author,$iDisplayStart,$iDisplayLength,$game_id);
				for($i = 0 ; $i < count($json) ; $i++){
					$json[$i][] = '<input type="checkbox" name ="caseVersions" class="checkboxes" value="'.$json[$i][0].'">';  //往数组尾插入元素
				}
				$Total=$caseModel->getTotalreS($search,$contentArrs,$author,$game_id);
			}
			// $Total=$Total[0][0];
			$json=array(
				"iDisplayLength"=>$iDisplayLength,
				"iTotalRecords"=> $Total,//返回的总数据
				"iTotalDisplayRecords"=> $Total,  //过滤后的数据
				"aaData"=>$json,
					
			);
			
			echo json_encode($json);
		
			return false;
		}

		/**
		*跳转到显示当前任务下的所有案例
		*
		*/
		public function showCaseByTaskAction()
		{
			$task_id = @$_GET['task_id'];
			$task_name = @$_GET['task_name'];
			$taskModel = new Models_Task_task();
			$game_v_id = $taskModel->getGameVersionByTaskId($task_id);
			return $this->getView()->assign(['task_name'=>$task_name,'task_id'=>$task_id,'game_v_id'=>$game_v_id]);

		}
		/**
		*查询当前任务下所有案例
		*/
		public function listCaseByTaskAction()
		{
			$task_id = @$_GET['task_id'];
			$iDisplayStart=$_REQUEST['iDisplayStart'];
			$iDisplayLength=$_REQUEST['iDisplayLength'];

			$caseModel =new Models_Case_Case();
			$res=$caseModel->summaryReport($task_id);
			$Total=$caseModel->getTotalReport($task_id);
			$json=array(
				"iDisplayLength"=>$iDisplayLength,
				"iTotalRecords"=> $Total[0][0],//返回的总数据
				"iTotalDisplayRecords"=> $Total,  //过滤后的数据
				'aaData'=>$res,
			);
			echo json_encode($json);
			return false;
		}

		/**
		*跳转查询我的一个任务下的所有案例页面
		*/
		public function showCasesUnderTaskAction()
		{
			$task_id = @$_GET['task_id'];
			$taskModel = new Models_Task_task();
			$task_name = $taskModel->getTaskNameByTaskId($task_id);
			$gameVersionId = $taskModel->getGameVersionByTaskId($task_id);
			return $this->getView()->assign(["task_id"=>$task_id,"task_name"=>$task_name,"gameVersionId"=>$gameVersionId]);
		}

		/**
		*查询我的一个任务下的所有案例
		*/
		public function listCasesUnderTaskAction()
		{
			$task_id = @$_GET['task_id'];
			$user_id = Yaf_Session::getInstance()->get('user_id');
			$iDisplayStart=$_REQUEST['iDisplayStart'];
			$iDisplayLength=$_REQUEST['iDisplayLength'];
			$taskModel = new Models_Case_Case();
			$json = $taskModel->listCasesUnderTasks($task_id,$iDisplayStart,$iDisplayLength);
			$len = count($json);
			for($i = 0; $i<$len; $i++)
			{
				if(0 == $json[$i][4])
					$json[$i][4]="<a href='#' class='commit btn btn-sm  green' value='".$json[$i][0]."'>提交</a>";
				else
					$json[$i][4]="<a class='hasCommit btn btn-sm red disabled'>已提交</a>";
			}
			$Total=$taskModel->getTotalCase($task_id,'tc_case_v','case_v_id','task_id');
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

		//提交案例
		public function commitCaseAction()
		{
			$case_v_id = @$_POST['case_v_id'];
			$user_id = Yaf_Session::getInstance()->get("user_id");
			$caseModel = new Models_Case_Case();
			$json = $caseModel->commitCase($user_id,$case_v_id);
			$json = array('code' => $json);
			echo json_encode($json);
			return false;
		}
//-----------------------v2 end------------------------------------------------

//-----------------------not be used------------------------------------------
		//添加案例(目前由导入进行添加)
		public function addAction(){
			
			$tc_testcase_name=@$_POST['case_name'];
			$gameName = @$_POST['gameName'];
			$gameVersion = @$_POST['gameVersion'];
			$tester_id = @$_POST['tester'];
			$assign = Yaf_Session::getInstance()->get("user_id");
			//echo $tc_testcase_name."..".$gameName."..".$gameVersion."..".$tester_id;
			if($tc_testcase_name&&$gameName&&$gameVersion&&$tester_id){
		
				//$book=array($bkname,$bkauthor,$bkprice,$bktime,$bkpublsih,$bkcount);
				$date=date("Y-m-d H:i:s");
				$testcase=array($tc_testcase_name,$gameName,$gameVersion,$tester_id,$assign,$date);
				$addModel=new Models_Case_Case();
				$res=$addModel->addCase($testcase);
				echo json_encode(array("code"=>$res));
			}else{
				echo json_encode(array("code"=>2));
			}
			return false;
		}
		public function editAction(){
			
			$plan_id=$_POST['plan_id'];
			$plan_name=@$_POST['plan_name'];
			$exe_level=@$_POST['exe_level'];
			$project=@$_POST['project'];
			$build=@$_POST['build'];
			$sku=@$_POST['sku'];
			$valbyqa=@$_POST['valbyqa'];
			$res=2;
			if($plan_id){
				$plan=array($plan_id,$plan_name,$exe_level,$valbyqa,$project,$build,$sku);
				$editModel=new Models_Case_Case();
				$res=$editModel->editPlan($plan);
			}
			echo json_encode(array("code"=>$res));
			return false;
		}
		public function deleteCaseAction(){
			
			$ids=$_POST['case_v_ids'];
			$ids=explode(",", $ids);
			$game_id = Yaf_Session::getInstance()->get("game_id");
			$delModel=new Models_Case_Case();

			$res=$delModel->deleteCase($ids,$game_id);
			echo json_encode(array("code"=>$res));
			return  false;
		}
		//归档
		public function fileAction()
		{
			
			$ids=$_POST['ids'];
			$ids=explode(",", $ids);
			$fileModel=new Models_Case_Case();
			$res=$fileModel->fileCase($ids);
			echo json_encode(array('code'=>$res));
			return false;
		}
		
		public function showcountbyidAction(){
			
			$start_time = $_POST['start_time'];
			$end_time=$_POST['end_time'];
			$user_id = @$_POST['user_id'];	
			//$user_id = $_SESSION['user_id'];
			$viewNum = new Models_Case_Case();
			if(!$user_id){
				$viewNum = $viewNum->fetchpast($start_time,$end_time, "");
// 				$viewNum=array(
// 					$viewNum,
// 				);
			echo json_encode($viewNum);
			}else{
				$viewNum = $viewNum->fetchpast($start_time,$end_time, $user_id);
			echo json_encode($viewNum);
			}
			return false;
			
		}

		public function getgameversionAction()
		{
			
			$gameName = @$_POST['gameName'];
			//echo $gameName;
			$caseModel = new Models_Case_Case();
			$json = $caseModel->getGameVersion($gameName);
		
			echo json_encode($json);
			return false;
		}
//--------------------------not be used--------------------------------------------------------
	}
