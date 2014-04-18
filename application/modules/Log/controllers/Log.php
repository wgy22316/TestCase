<?php
class LogController extends  Yaf_Controller_Abstract{
	public function indexAction(){
		
	}
	public function queryuseralllogaction(){
		$log = new Models_Log_Log();
		//$user_id = Yaf_Session::getInstance()->get("user_id");
		$result = $log->queryLog();
		$arr=json_encode($result);
		echo $arr;
		return false;
	}
	
	public function querylogbytimeaction(){
		$log = new Models_Log_Log();
		$fromTime = $_POST['fromTime'];
		$toTime = $_POST['toTime'];
		if($toTime == null && $fromTime != null){
			$arrFrom = explode("/", $fromTime);
			$fyear=$arrFrom[2]; $fmonth=$arrFrom[0]; $fday=$arrFrom[1];
			$fromTime = $fyear."/".$fmonth."/".$fday;
			
			$tyear=$arrFrom[2]; $tmonth=$arrFrom[0]; $tday=$arrFrom[1] +1;
			$toTime = $tyear."/".$tmonth."/".$tday;
			$result = $log->queryLogBytime($fromTime, $toTime);
			//print_r($result);
			$arr=json_encode($result);
			echo $arr;
			return false;
		}
		
		if($toTime != null && $fromTime == null){
			$arrTo = explode("/", $toTime);
			$tyear=$arrTo[2]; $tmonth=$arrTo[0]; $tday=$arrTo[1] +1;
			$toTime = $tyear."/".$tmonth."/".$tday;
			
			$fyear=$arrTo[2]; $fmonth=$arrTo[0]; $fday=$arrTo[1];
			$fromTime = $fyear."/".$fmonth."/".$fday;
					
			$result = $log->queryLogBytime($fromTime, $toTime);
			//print_r($result);
			$arr=json_encode($result);
			echo $arr;
			return false;
		}
		
		if($toTime != null && $fromTime != null){
			$arrFrom = explode("/", $fromTime);
			$fyear=$arrFrom[2]; $fmonth=$arrFrom[0]; $fday=$arrFrom[1];
			$fromTime = $fyear."/".$fmonth."/".$fday;
			
			$arrTo = explode("/", $toTime);
			$tyear=$arrTo[2]; $tmonth=$arrTo[0]; $tday=$arrTo[1] +1;
			$toTime = $tyear."/".$tmonth."/".$tday;
			
			$result = $log->queryLogBytime($fromTime, $toTime);
			//print_r($result);
			$arr=json_encode($result);
			echo $arr;
		}
		return false;
	}
}