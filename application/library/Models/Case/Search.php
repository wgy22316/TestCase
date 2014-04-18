<?php
class Models_Case_Search{

	/**
	 * 搜索用例
	 * zhoujx
	 * 1.通过game_id和搜索内容搜索结果，
	 * 	如果结果为空 return null
	 * 	如果结果不为空 return 搜索结果
	 */
	public function findSearch($value,$game_id){

// 		try{
// 			$instance = new DBTool_DBUtil();
// // 			$sql = "SELECT tc_case_v.case_id,tc_case.casename,tc_case_v.version,tc_userinfo.username,tc_case_v.create_time,tc_case_v.commit_time,tc_case_v.case_v_id
// // 			from tc_testcase as tct
// // 			LEFT JOIN tc_case_v on tc_case_v.case_v_id = tct.case_v_id 
// // 			LEFT JOIN tc_case on tc_case.case_id = tc_case_v.case_id
// // 			LEFT JOIN tc_userinfo on tc_case_v.author_id = tc_userinfo.user_id
// // 			LEFT JOIN tc_game_v on tc_game_v.game_v_id = tc_case_v.game_v_id
// // 			where tc_game_v.game_id = '".$game_id."' and (tct.title LIKE '%".$value."%' or tct.Initial_condition LIKE '%".$value."%' or tct.procedures LIKE '%".$value."%' or tct.expected_result LIKE '%".$value."%')
// // 			GROUP BY tc_case_v.version ,tc_case_v.case_id
// // 			order by tc_case.casename asc "; 
// 			//根据模糊匹配查询于搜索内容相关的case_v_id
// 			$sql_testcase = "select tc_testcase.case_v_id ,tc_userinfo.username from tc_testcase , tc_userinfo where title LIKE '%".$value."%' or Initial_condition LIKE '%".$value."%' or procedures LIKE '%".$value."%' or expected_result LIKE '%".$value."%' ;";		
// 			$case_v_id=$instance->executeDQL($sql_testcase,MYSQL_ASSOC);
// 			if(empty($case_v_id)){
// 				$instance->close_conn();
// 				return null;
// 			}
// 			else{	//根据case_v_id到tc_case_v表中查询用例信息
// 					$case_v_id_str=implode(',',$case_v_id);//将查询的结果的用例版本id转换为字符串
// 					$sql_case_v = "select * from tc_testcase where vase_v_id in (".$case_v_id_str.") ;";
// 					$case_v=$instance->executeDQL($sql_case_v,MYSQL_ASSOC);
// 					$case_id = array();
// 					$author_id = array();
// 					for($i=0,$length=sizeof($case_v);$i<$length;$i++){
// 						array_push($case_id,$case_v[$i]['case_id']);
// 						array_push($author_id,$case_v[$i]['author_id']);
// 					}
					
// 			}
// 		}catch(Exception $e){
// 			$instance->close_conn();
// 			return null;
// 		}

// 		$instance->close_conn();
// 		return null;
	}
	//查看用例详情
	public function findDetail($id){
		try{
			$instance = new DBTool_DBUtil();
			$sql = "select case_v_id,tc_id,title,Initial_condition,procedures,expected_result from tc_testcase where case_v_id= '".$id."' ;";

			$result=$instance->executeDQL($sql,MYSQL_ASSOC);
			if(empty($result)){
				$instance->close_conn();
				return null;
			}
			else{
				$instance->close_conn();
				return $result;
			}
		}catch(Exception $e){
			$instance->close_conn();
			return null;
		}

		$instance->close_conn();
		return null;

	}
	//添加用例
	public function addMyCase($case_v_id,$task_id,$game_v_id){
		try{
			$instance = new DBTool_DBUtil();
			// $id=3;
			$sql = "select case_id  from tc_case_v where case_v_id= '".$case_v_id."' ;";

			$result=$instance->executeDQL($sql,MYSQL_ASSOC);
			if($result==null){
			// printf("null");
				$instance->close_conn();
				$array = array("state"=>"fail","error"=>"需要添加的用例为空");
				return $array;
			}
			else{
				$case_id = $result[0]['case_id'];

				$user_id = Yaf_Session::getInstance()->get('user_id');
				//
				// $game_v_id = 1;
				//判断是否已经添加过改用例，如果添加，判断是否已经提交
				//当未添加和添加已提交则记录新的用例版本 isexist=false，如果添加未提交则覆盖isexist=true

				$isexist = $this->uncommit($case_id,$task_id,$game_v_id,$user_id);
				// printf($isexist);
				//存在一条未提交的记录
				if($isexist){
					//
					$my_case_v_id = $isexist;//
					//如果当前要添加的用例是我的未提交的版本,相当于自己添加自己未提交的，不用继续操作
					// printf($my_case_v_id);
					// printf($case_v_id);
					if($my_case_v_id==$case_v_id){
						// printf("is my");
						$instance->close_conn();
						$array = array("state"=>"fail","error"=>"当前要添加的用例是你的未提交的版本,不需要再次添加");
						return $array;
					}
					//如果不是，需要把我的未提交用例的数据删除，同时把搜索岛的数据添加到我的用例
					else{
						// printf("not is my");
						$dele = "delete from tc_testcase where case_v_id='".$my_case_v_id."' ;";
						$deleresult = $instance->executeDML($dele);
						if($deleresult){
							$result_case_v = $my_case_v_id ;
							// $instance->close_conn();
							// return true;
						}
					}
				}else{
					$max_sql = "select max(version) from tc_case_v where case_id= '".$case_id."' ;";
					$result=$instance->executeDQL($max_sql,MYSQL_ASSOC);
					// print_r($result);
					$max_version = $result[0]['max(version)'];
					// printf($max_version);
					$maxarray = explode('.', $max_version);
					$addone = $maxarray[1]+1;
					$max = $maxarray[0].'.'.$addone;
					$nowdate=date("Y-m-d H:i:s");
					$commit_time=date("9999-12-29 23:59:59");
					$array = array('issssiii',$case_id,$max,$user_id,$nowdate,$commit_time,$game_v_id,$task_id,0);
					$insertsql = "insert into tc_case_v(case_id,version,author_id,create_time,commit_time,game_v_id,task_id,is_commit) values(?,?,?,?,?,?,?,?)";
					$result_case_v=$instance->executeInsert($insertsql,$array);//获取刚插入tc_case_v 记录的id
				}
					//添加新的用例到tc_testcase
					//查找所有用例内容
				$find_tc_testcase_sql = "select * from tc_testcase where case_v_id = '".$case_v_id."' ;";
				$tc_tt = $instance->executeDQL($find_tc_testcase_sql,MYSQL_ASSOC);
				// printf($result_case_v);
				$i = 0;
				$length=sizeof($tc_tt);
				// printf("length=".$length);
				for(;$i<$length;$i++){
					$insertsql = "insert into tc_testcase(case_v_id,tc_id,title,";
					$insertsql .="Initial_condition,procedures,expected_result)";
					$insertsql .="values(?,?,?,?,?,?)";
					$testcase_array =array('iissss',$result_case_v,$tc_tt[$i]['tc_id'],$tc_tt[$i]['title'],$tc_tt[$i]['Initial_condition'],$tc_tt[$i]['procedures'],$tc_tt[$i]['expected_result']);
					$result=$instance->executeInsert($insertsql,$testcase_array);//获取刚插入tc_case_v 记录的id
					// printf($insertsql);
				}
				if($i == $length){
					$instance->close_conn();

					$array = array("state"=>"success");
					return $array;
				}
				$instance->close_conn();

				$array = array("state"=>"fail","error"=>"添加失败，请重新添加");
				return $array;
			}
			$instance->close_conn();
				$array = array("state"=>"fail","error"=>"添加失败，请重新添加");
				return $array;

		}catch(Exception $e){
			$instance->close_conn();
				$array = array("state"=>"fail","error"=>"添加失败，请重新添加");
				return $array;
		}

		$instance->close_conn();
				$array = array("state"=>"fail","error"=>"添加失败，请重新添加");
				return $array;
	}
	//搜索是否有一条当前游戏版本人物我未提交的记录
	//如果没有 return false
	public function uncommit($case_id,$task_id,$game_v_id,$user_id){
		$instance = new DBTool_DBUtil();
		$sql = "select * from tc_case_v where case_id= '".$case_id."' and author_id = '".$user_id."'
				and game_v_id ='".$game_v_id."' and task_id='".$task_id."' and is_commit=0;";
		// printf($sql);
		$result=$instance->executeDQL($sql,MYSQL_ASSOC);
		if(empty($result)){
			// printf("empty");
			return false;
		}
		else{
			// printf($result[0]["case_v_id"]);
			return $result[0]["case_v_id"];
		}

	}


}