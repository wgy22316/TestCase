<?php


class Models_Case_Case{
	
//------------------------------v2 start-------------------

	public function getGameNamesById($game_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$res = $dbUtil->executeDQL("select gamename from tc_game where game_id=".$game_id, MYSQLI_NUM);
		return $res[0][0];
	}
	/**
	*查询所属游戏下的所有案例信息
	*/
	public function getCasesBaseImf($search,$start,$length,$game_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "SELECT tcv.case_id,tc_case.casename,count(tcv.case_v_id),tc_userinfo.username,tcv.create_time
					from tc_case_v as tcv
					LEFT JOIN tc_case on tc_case.case_id = tcv.case_id
					LEFT JOIN tc_userinfo on tc_userinfo.user_id = tcv.author_id and tcv.version='v1.0'
					where tc_case.game_id = ".$game_id;
		if(""!=$search&&$search){
			$sql.=" and tc_case.casename like '%".$search."%'";
		}
		$sql.=" GROUP BY tcv.case_id order by tcv.case_id asc";
		if($length!="-1"){
		$sql.=" limit ".intval($start).",".intval($length);
		}

		//file_put_contents("SQL.log", "getcase---sql语句：".$sql."\r\n",FILE_APPEND);
		//caseid,casename,count(caseid)版本数
		$res1 = $dbUtil->executeDQL($sql, MYSQLI_NUM);


		$sql = "select author_id,create_time from tc_case_v where version like 'v1.0' order by case_id asc";
		//file_put_contents("SQL.log", "getcase2---sql语句：".$sql."\r\n",FILE_APPEND);
		$res2 = $dbUtil->executeDQL($sql, MYSQLI_NUM);
		
		// var_dump($res1);
		// echo "hello<br>";
		// var_dump($res2);
		for ($i = 0; $i<count($res1);$i++) {
			$res1[$i][]=$res2[$i][0];
			$res1[$i][]=$res2[$i][1];
		}
		return $res1;

	}

	/**
	*查询游戏下的所有案例
	*
	*/
	public function getAllCases($start,$length,$game_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "SELECT tc_case_v.case_v_id,tc_game.gamename,tc_case.casename,tc_case_v.version,tc_userinfo.username,tc_case_v.create_time
						from tc_case_v,tc_case,tc_game,tc_userinfo
						where tc_case_v.case_id = tc_case.case_id and tc_case.game_id = tc_game.game_id and tc_case_v.author_id=tc_userinfo.user_id
						ORDER BY tc_case.casename,tc_case_v.version ASC";
		$array = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		$case_v_id = array();
		if(empty($array)){
				$dbUtil->close_conn();
				return array();
		}
		else{
			$case_v_idstr=$array[0][0];//将查询的所拥有组的菜单转换为字符串
			$case_v_id[] = $array[0][0];
			for($stri=1,$strlength=sizeof($array);$stri<$strlength;$stri++){
				$case_v_idstr.=",".$array[$stri][0];
				$case_v_id[] = $array[$stri][0];
			}
			$sqlcount = "select case_v_id,count(case_v_id) from tc_testcase where case_v_id in (".$case_v_idstr.") group by case_v_id ";
			$case_count = $dbUtil->executeDQL($sqlcount,MYSQLI_NUM);
			$dbUtil->close_conn();
			$key = 0;
			$res = array();
			$length = sizeof($case_count);
			for($i = 0,$len = sizeof($array); $i<$len; $i++)
			{
				if(0 == $length)
				{
					$res[] = array($array[$i][0],$array[$i][1],$array[$i][2],$array[$i][3],"0",$array[$i][4],$array[$i][5]);
					continue;
				}
				for($j = 0; $j<$length; $j++)
				{
					if($array[$i][0] == $case_count[$j][0])
					{
						$res[] = array($array[$i][0],$array[$i][1],$array[$i][2],$array[$i][3],$case_count[$j][1],$array[$i][4],$array[$i][5]);
						$key = 1;
						break;
					}
				}
				if($key == 1)
				{
					$key = 0;
					continue;
				}
				$res[] = array($array[$i][0],$array[$i][1],$array[$i][2],$array[$i][3],"0",$array[$i][4],$array[$i][5]);
/*				if(0 == sizeof($case_count) || $array[$i][0] != $case_count[$key][0])
				{
					$res[] = array($array[$i][0],$array[$i][1],$array[$i][2],$array[$i][3],"0",$array[$i][4],$array[$i][5]);
				}
				else
				{
					$res[] = array($array[$i][0],$array[$i][1],$array[$i][2],$array[$i][3],$case_count[$key][1],$array[$i][4],$array[$i][5]);
					$key++;
				}*/
			}
			return $res;
		}

	}

	/**
	*查询游戏下的所有案例信息（一次搜索）
	*/
	public function getAllVersionCasesImf($search,$start,$length,$game_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "select case_v_id  from tc_testcase 
				where 	title LIKE '%".$search."%' or 
						Initial_condition LIKE '%".$search."%' or 
						procedures LIKE '%".$search."%' or 
						expected_result LIKE '%".$search."%' 
				GROUP BY case_v_id
				ORDER BY case_v_id asc";
		if($length!="-1"){
			$sql.=" limit ".intval($start).",".intval($length);
		}

		// file_put_contents("SQL.log", "第一次搜索1 ".$sql."\r\n",FILE_APPEND);

		$case_v_id = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		if(empty($case_v_id)){
				$dbUtil->close_conn();
				return array();
		}
		else{
			$case_v_idstr=$case_v_id[0][0];//将查询的所拥有组的菜单转换为字符串
			for($stri=1,$strlength=sizeof($case_v_id);$stri<$strlength;$stri++){
				$case_v_idstr.=",".$case_v_id[$stri][0];
			}
			$sqlcount = "SELECT count(case_v_id) from tc_testcase where case_v_id in (".$case_v_idstr.") GROUP BY case_v_id ORDER BY case_v_id ASC";
			// file_put_contents("SQL.log", "第一次搜索2 ".$sqlcount."\r\n",FILE_APPEND);
			$case_count = $dbUtil->executeDQL($sqlcount,MYSQLI_NUM);
			$sqlgame = "SELECT tc_game.gamename,tc_case.casename,tc_case_v.version,tc_userinfo.username,tc_case_v.create_time
						from tc_case_v,tc_case,tc_game,tc_userinfo
						where tc_case_v.case_id = tc_case.case_id and tc_case.game_id = tc_game.game_id and tc_case_v.author_id=tc_userinfo.user_id
						and tc_case_v.case_v_id in (".$case_v_idstr.")  ORDER BY tc_case_v.case_v_id  ASC";
			// file_put_contents("SQL.log", "第一次搜索3 ".$sqlgame."\r\n",FILE_APPEND);
			$case_game = $dbUtil->executeDQL($sqlgame,MYSQLI_NUM);
			if(empty($case_game)){
				$dbUtil->close_conn();
				return array();
			}
			else{
				$array = array();
				for($i=0,$length=sizeof($case_v_id);$i<$length;$i++){
					$array[] = array($case_v_id[$i][0],$case_game[$i][0],$case_game[$i][1],$case_game[$i][2],$case_count[$i][0],$case_game[$i][3],$case_game[$i][4]);
				}
				// var_dump($array);
				return $array;
			}

		}
	}

	/**
	*二次查询筛选游戏下的案例
	*/
	public function getResearchResult($search,$contentArrs,$author,$start,$length,$game_id)
	{
		if(''!=$contentArrs && null!=$contentArrs)
			$contentArrs = explode(",", $contentArrs);
		else
			$contentArrs=[];
		$dbUtil = new DBTool_DBUtil();
		$sql = "select case_v_id  from tc_testcase";
		if(0==count($contentArrs))
			$sql.= " where title like '%".$search."%' or Initial_condition like '%".$search."%' or procedures like '%".$search."%' or expected_result like '%".$search."%'";
		else
		{
			$sql.=" where ".$contentArrs[0]." like '%".$search."%'";
			for($i=1;$i<count($contentArrs);$i++)
			{
				$sql.=" and ".$contentArrs[$i]." like '%".$search."%'";
			}
		}
		$sql.=" GROUP BY case_v_id ORDER BY case_v_id asc";
		if(''==$author || null==$author)
		{
			if($length!="-1"){
					$sql.=" limit ".intval($start).",".intval($length);
			}
		}
		// file_put_contents("SQL.log", "二次搜索第一次查询 ".$sql."\r\n",FILE_APPEND);
		
		$case_v_id = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		if(empty($case_v_id)){
				$dbUtil->close_conn();
				return array();
		}
		else{
			$case_v_idstr=$case_v_id[0][0];//将查询的所拥有组的菜单转换为字符串
			for($stri=1,$strlength=sizeof($case_v_id);$stri<$strlength;$stri++){
				$case_v_idstr.=",".$case_v_id[$stri][0];
			}
			if(''!=$author && null!=$author)
			{
				$sqlreal = "SELECT case_v_id from tc_case_v,tc_userinfo
							where tc_case_v.case_v_id in (".$case_v_idstr.") and tc_case_v.author_id = tc_userinfo.user_id and tc_userinfo.username like '%".$author."%'
							ORDER BY tc_case_v.case_v_id ASC";
				if($length!="-1"){
					$sql.=" limit ".intval($start).",".intval($length);
				}

				// file_put_contents("SQL.log", "二次搜索第二次查询 ".$sql."\r\n",FILE_APPEND);
				$case_v_id = $dbUtil->executeDQL($sqlreal,MYSQLI_NUM);
				if(empty($case_v_id)){
					$dbUtil->close_conn();
					return array();
				}
				else{
					$case_v_idstr=$case_v_id[0][0];//将查询的所拥有组的菜单转换为字符串
					for($stri=1,$strlength=sizeof($case_v_id);$stri<$strlength;$stri++){
						$case_v_idstr.=",".$case_v_id[$stri][0];
					}
				}	
			}
			
			$sqlcount = "SELECT count(case_v_id) from tc_testcase where case_v_id in (".$case_v_idstr.") GROUP BY case_v_id ORDER BY case_v_id ASC";
			
			$case_count = $dbUtil->executeDQL($sqlcount,MYSQLI_NUM);
			$sqlgame = "SELECT tc_game.gamename,tc_case.casename,tc_case_v.version,tc_userinfo.username,tc_case_v.create_time
						from tc_case_v,tc_case,tc_game,tc_userinfo
						where tc_case_v.case_id = tc_case.case_id and tc_case.game_id = tc_game.game_id and tc_case_v.author_id=tc_userinfo.user_id
						and tc_case_v.case_v_id in (".$case_v_idstr.")  ORDER BY tc_case_v.case_v_id  ASC";

			$case_game = $dbUtil->executeDQL($sqlgame,MYSQLI_NUM);
			if(empty($case_game)){
				$dbUtil->close_conn();
				return array();
			}
			else{
				$array = array();
				for($i=0,$length=sizeof($case_v_id);$i<$length;$i++){
					$array[] = array($case_v_id[$i][0],$case_game[$i][0],$case_game[$i][1],$case_game[$i][2],$case_count[$i][0],$case_game[$i][3],$case_game[$i][4]);
				}
				// var_dump($array);
				return $array;
			}
		}

	}

	public function listCasesUnderTasks($task_id,$start,$length)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "SELECT tcv.case_v_id,tc_case.casename,tcv.version,tcv.create_time,tcv.is_commit
				from tc_case_v as tcv
				LEFT JOIN tc_case on tc_case.case_id = tcv.case_id
				where tcv.task_id = ".$task_id."
				group by tcv.case_id ,tcv.version
				order by tcv.create_time desc";
		if($length!="-1"){
			$sql.=" limit ".intval($start).",".intval($length);
		}
		return $dbUtil->executeDQL($sql,MYSQLI_NUM);
	}


	public function commitCase($user_id,$case_v_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$commitdate = date('Y:m:d H:i:s');
		$sql = "update tc_case_v set is_commit = 1 ,commit_time= '".$commitdate."' where author_id=".$user_id." and case_v_id=".$case_v_id;

		return $dbUtil->executeDML($sql);
	}

	//判断该版本是否已提交
	public function isCommit($case_v_id,$user_id)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "SELECT case_v_id from tc_case_v where is_commit = 1 and author_id = ".$user_id." and case_v_id=".$case_v_id;
		$res = $dbUtil->executeDQL($sql, MYSQLI_NUM);
		if(count($res)==0)
			return false;
		else
			return true;
	}


	//依据case_v_id 批量删除数据库中的tc_case_v,tc_testcase
	public function deleteCase($ids,$game_id)
	{
			$dbUtil=new DBTool_DBUtil();
			$ids=implode(",", $ids);
			$sql1="delete from tc_testcase where case_v_id in (".$ids.")";
			$sql2="delete from tc_case_v where case_v_id in (".$ids.")";
			$sqls =array($sql1,$sql2);

			// file_put_contents("SQL.log", "删除1 ".$sql."\r\n",FILE_APPEND);
			if ($dbUtil->executeMutilSQL($sqls)){//判断tc_case表中的每条数据都有对应的版本号，如没有则删掉
				$sql = "SELECT tc_case.case_id,count(tc_case_v.case_id) from tc_case
					 LEFT JOIN tc_case_v on tc_case.case_id = tc_case_v.case_id 
					 where tc_case.game_id = ".$game_id."
					 GROUP BY tc_case.case_id";
				$res = $dbUtil->executeDQL($sql,MYSQLI_NUM);
				$caseArr = array();
				for($i = 0; $i <count($res); $i++)
				{
					if($res[$i][1] == 0)
						$caseArr[] = $res[$i][0];
				}
				if(count($caseArr)>0)
				{
					$caseArr = implode(",", $caseArr);
					$sql = "delete from tc_case where case_id in (".$caseArr.")";
					if(!$dbUtil->executeDML($sql))
					{
						return "3";
					}
				}
				return "1";
			}else{
				return "2";
			}
	}
//------------------------------not be used---------------------	



	public function fetchpast($start_time,$end_time,$user_id)
	{
		$DBUtil = new DBTool_DBUtil();
		if(!$user_id){
		$sql="SELECT  author_id,count(tc_id) as cases from tc_test_case where create_time between '".$start_time."' and '".$end_time."' group by author_id";
		}else{
		$sql="SELECT count(tc_id) as cases  from tc_test_case where create_time between '".$start_time."' and '".$end_time."' and author_id= '".$user_id."'";
		}
	//	file_put_contents("SQL.log", $sql."\r\n",FILE_APPEND);
		$res = $DBUtil->executeDQL($sql,MYSQL_NUM);
		return $res;
	}
	
	public function getFileNameById($versionId){
			$dbUtil =new DBTool_DBUtil();
			$sql="SELECT tc_testcase_name from tc_version where versionId='".$versionId."'";
			$dbUtil->executeDML("set names utf8");
			$res=$dbUtil->executeDQL($sql,MYSQL_NUM);
			return $res[0][0];
	}
	//依据versionId 批量归档tc_test_case中的数据
	public function fileCase($ids)
	{
		$dbUtil = new DBTool_DBUtil();
		$ids=implode(",", $ids);
		$sql = "SELECT * from tc_test_case where versionId in (".$ids.")";
		$data = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		if($data)
		{
			$sql1 = "insert into tc_file values";
			$k=1;
			foreach ($his as $v) {
				if($k==1)
				{
					$sql1.="(null";
					$k=0;
				}
				else
					$sql1.=",(null";
				for ($i=1;$i<count($v);$i++) {
					$sql1.=",'".$v[$i]."'";
				}
				$sql1.=")";
			}
		}
		$sql2 = "update tc_version set isfile = 1 where versionId in (".$ids.")";
		return $dbUtil->executeMutilSQL(array($sql1,$sql2));
	}
	//判断是否存在已提交case，若存在则返回false
	public function checkCaseisCommit($ids)
	{
		$dbUtil = new DBTool_DBUtil();
		$ids=implode(",", $ids);
		$sql = "select case_v_id from tc_case_v where case_v_id in (".$ids.") and is_commit = 1";
		//file_put_contents("SQL.log", $sql."\r\n",FILE_APPEND);
		$res = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		if(count($res)>0)
			return false;
		else
			return true;
	}


	public function editPlan($arr){
		$dbUtil=new DBTool_DBUtil();
		//$plan=array($versionId,$tc_testcase_name,$exe_level,$valbyqa,$project,$build,$sku);
		$sql="update tc_version  set tc_testcase_name='".$arr[1]."', execution_level='".$arr[2]."' ,validated_by_qa='".$arr[3]."',project='".$arr[4]."',build='".$arr[5]."',sku='".$arr[6]."' where versionId='".$arr[0]."'";
	//	file_put_contents("SQL.log", "editBook---sql语句：".$sql."\r\n",FILE_APPEND);
		return 	$dbUtil->executeDML($sql);
	}
	
	//创建新案例
	public function addCase($arr){
		$dbUtil=new DBTool_DBUtil();
		$sql = "select gameId from tc_game where gameName='".$arr[1]."' and gameVersion='".$arr[2]."'";
		$gameId = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		// file_put_contents("SQL.log", "addCase---gameId：".$gameId[0][0]."\r\n",FILE_APPEND);
		$sql = "select version, isfile from tc_version where gameId=".$gameId[0][0]." and tc_testcase_name='".$arr[0]."' order by version desc";
		$arrversion = $dbUtil->executeDQL($sql,MYSQLI_NUM);
		// if($arrversion)
		// 	file_put_contents("SQL.log", "addCase---arr：".$arrversion[0][0]."----".$arrversion[0][1]."\r\n",FILE_APPEND);
		if($arrversion)//存在以前的版本
		{
			if(0==$arrversion[0][1])//旧版本还未提交归档，无法创建新版本
			{
				return 5;//告知用户旧版本未提交，请修改旧版本并提交完成后再次创建
			}
			else//旧版本已提交，创建新版本
			{
				$strVersion = $arrversion[0][0];
				str_ireplace("v", "", $strVersion);
				$a = explode(".", $strVersion);
				if($a[2]>=9)
				{
					$a[2]=0;
					if($a[1]>=9)
					{
						$a[1]=0;
						$a[0]++;
					}
					else
						$a[1]++;
				}
				else
					$a[2]++;
				$strVersion = "v".$a[0].".".$a[1].".".$a[2];
				
			}
		}
		else//不存在旧版本
		{
			$strVersion="v1.0.0";
		}
		$sql = "insert into tc_version values(null,?,?,?,?,?,?,?)";
		$res = $dbUtil->executeSTMT($sql,array('ississs',$gameId,$arr[0],$strVersion,0,$arr[5],$arr[3],$arr[4]));
		return $res;
	}

	/**
	 * 获取列表
	 * @param unknown $search  模糊查询的参数
	 * @param unknown $start     分页 开始
	 * @param unknown $length  分页长度
	 * @return 返回数组
	 */	
	public function listplan($search,$start,$length,$user_id=''){
		$dbUtil=new DBTool_DBUtil();
		$sql="select versionId,tc_testcase_name,g.gameName,g.gameVersion,version,assign,tester_id,create_time 
			from tc_version as v, tc_game as g where v.gameId=g.gameId";
		if(""!=$search&&!$search){
			$sql.=" and tc_testcase_name like '%".$search."%'";
		}
		if (""!=$user_id){
			$sql.=" and assign=".$user_id;
			//versionId in (SELECT  DISTINCT versionId FROM tc_test_case where author_id='2089'
		}
		if($length!="-1"){
		$sql.=" limit ".intval($start).",".intval($length);
		}		
//		file_put_contents("SQL.log", "listplan---sql语句：".$sql."\r\n",FILE_APPEND);
		
		$res=$dbUtil->executeDQL($sql,MYSQLI_NUM);
		//echo  $dbUtil->executeDQL("select found_rows()", MYSQL_NUM)[0][0];

		return $res;
	}

	public function listmyexecute($user_id,$start,$length)
	{
		$dbUtil=new DBTool_DBUtil();

		$sql="select versionId,tc_testcase_name,g.gameName,g.gameVersion,version,assign,tester_id,create_time 
			from tc_version as v, tc_game as g where v.gameId=g.gameId and isfile = 0 and tester_id = ".$user_id;
		if($length!="-1"){
			$sql.=" limit ".intval($start).",".intval($length);
		}
		$res=$dbUtil->executeDQL($sql,MYSQLI_NUM);
		return $res;
	}

	
	/**
	 * 获取数据库总记录数
	 */
	public function getTotal(){
		$dbUtil=new DBTool_DBUtil();
		$sql="select count(case_v_id) from tc_case_v";
		$res = $dbUtil->executeDQL($sql, MYSQL_NUM);
		return $res[0][0];
	}
	public function getmyexecuteTotal($user_id)
	{
		$dbUtil=new DBTool_DBUtil();
		$sql="select count(versionId) from tc_version as v, tc_game as g where v.gameId=g.gameId and isfile = 0 and tester_id = ".$user_id;
//		file_put_contents("SQL.log", "getTotal()---sql语句：".$sql."\r\n",FILE_APPEND);
		return $dbUtil->executeDQL($sql, MYSQL_NUM);
	}
	public function getmycaseTotal($sSearch,$user_id){
		$dbUtil=new DBTool_DBUtil();
		$sql="";
		if($user_id){
			$sql="select count(versionId) from tc_version where assign=".$user_id;
		}
		if($sSearch!=""){
			$sql.=" and tc_testcase_name like '%".$sSearch."%'";
		}
	//	file_put_contents("SQL.log", "getTotal()---sql语句：".$sql."\r\n",FILE_APPEND);
		return $dbUtil->executeDQL($sql, MYSQL_NUM);
	}

	/**
	*查询用户一周内创建用例数
	*/
	public function countNumCreate($user_id)
	{
		$dbUtil=new DBTool_DBUtil();
		
		$d = date("N", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
		// var_dump($d);
		$date=date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-$d, date("Y")));
		// var_dump($date);
		$sql="select count(tcId) from tc_test_case where versionId in (select versionId from tc_version where create_time > '".$date."' and assign = ".$user_id." )";
		$res = $dbUtil->executeDQL($sql, MYSQL_NUM)[0][0];
		// var_dump($res);
		$sql="select count(fileId) from tc_file where versionId in (select versionId from tc_version where create_time > '".$date."' and assign = ".$user_id." )";
		$res += $dbUtil->executeDQL($sql, MYSQL_NUM)[0][0];
		// echo($res);
		return $res;

	}
	/**
	*查询用户一周内执行用例数
	*/
	public function countNumExecute($user_id)
	{
		$dbUtil=new DBTool_DBUtil();
		
		$d = date("N", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
		// var_dump($d);
		$date=date("Y-m-d" ,mktime(0, 0, 0, date("m")  , date("d")-$d, date("Y")));
		// var_dump($date);
		
		$sql="select count(fileId) from tc_file where create_time > '".$date."' and versionId in (select versionId from tc_version where tester_id = ".$user_id." )";
		$res = $dbUtil->executeDQL($sql, MYSQL_NUM)[0][0];	
		return $res;
	}
	/**
	*查询所有存在的游戏名
	*/
	public function getGameNames()
	{
		$dbUtil = new DBTool_DBUtil();

		$sql = "select DISTINCT gameName from tc_game";
		return $dbUtil->executeDQL($sql,MYSQLI_NUM);
	}
	/**
	*查询指定游戏的版本号
	*/
	public function getGameVersion($gameName)
	{
		$dbUtil = new DBTool_DBUtil();
		$sql = "select gameVersion from tc_game where gameName like '".$gameName."'";
		return $dbUtil->executeDQL($sql, MYSQLI_NUM);
	}

//-------------------not be used------------------end--------------------------------------------

	public function getTotalCase($game_id,$tableName,$count,$element)
	{
		$dbUtil = new DBTool_DBUtil();
		return $dbUtil->executeDQL("select count(".$count.") from ".$tableName." where ".$element." = '".$game_id."'",MYSQLI_NUM);
	}

	//根据search查询案例条数
	 public function getTotals($search,$game_id)
	 {
	 	$dbUtil = new DBTool_DBUtil();
	 	$sql = "SELECT count(case_v_id) from tc_testcase";
		if(''!=$search && null !=$search)
			$sql.= " where title like '%".$search."%' or Initial_condition like '%".$search."%' or procedures like '%".$search."%' or expected_result like '%".$search."%'";
		$sql.=" GROUP BY case_v_id";
        $res = $dbUtil->executeDQL($sql,MYSQLI_NUM);
        return count($res);
	 }

	 //根据二次查询参数查询案例条数
	 public function getTotalreS($search,$contentArrs,$author,$game_id)
	 {
	 	$dbUtil = new DBTool_DBUtil();
	 	if(''!=$contentArrs && null!=$contentArrs)
			$contentArrs = explode(",", $contentArrs);
		else
			$contentArrs=[];
		
		$sql = "SELECT count(tc_case_v.case_v_id) from tc_case,tc_case_v
	 		LEFT JOIN tc_testcase on tc_testcase.case_v_id = tc_case_v.case_v_id
			LEFT JOIN tc_userinfo on tc_userinfo.user_id = tc_case_v.author_id
        	where tc_case.game_id = ".$game_id." and tc_case.case_id = tc_case_v.case_id";
		
		if(0==count($contentArrs))
			$sql.= " and (tc_testcase.title like '%".$search."%' or tc_testcase.Initial_condition like '%".$search."%' or tc_testcase.procedures like '%".$search."%' or tc_testcase.expected_result like '%".$search."%')";
		else
		{
			$sql.=" and tc_testcase.".$contentArrs[0]." like '%".$search."%'";
			for($i=1;$i<count($contentArrs);$i++)
			{
				$sql.=" and tc_testcase.".$contentArrs[$i]." like '%".$search."%'";
			}
		}
		if(''!=$author && null!=$author)
			$sql.=" and tc_userinfo.username like '%".$author."%'";
		
		$sql.=" group by tc_testcase.case_v_id";
		// file_put_contents("SQL.log", "gettotalreS---sql语句：".$sql."\r\n",FILE_APPEND);
		return count($dbUtil->executeDQl($sql,MYSQLI_NUM));
	 }

	/**
	* 获取该task_id下所有的结果记录数
	*/
	public function getTotalReport($task_id){
		$dbUtil=new DBTool_DBUtil();
		return $dbUtil->executeDQL("SELECT count(case_v_id) FROM tc_case_v WHERE task_id='".$task_id."'",MYSQLI_NUM);
	}
	/**
	*  获取Pass,Fail的条数，统计功能
	*/
	public function summaryReport($task_id){
		$dbUtil=new DBTool_DBUtil();
		$sql="SELECT 
		tc_case.casename,
		tc_case_v.version,
		count(tc_testcase.testcase_id),
		tc_case_v.case_v_id,
		tc_userinfo.username as 'total_case',
		tc_case_v.create_time,
		tc_case_v.commit_time 
		FROM tc_case_v
		LEFT JOIN tc_case ON tc_case.case_id = tc_case_v.case_id
		LEFT JOIN tc_userinfo ON tc_case_v.author_id = tc_userinfo.user_id
		LEFT JOIN tc_testcase ON tc_case_v.case_v_id = tc_testcase.case_v_id
		WHERE task_id = '".$task_id."' GROUP BY tc_testcase.case_v_id";
		//print_r($sql);
		//exit();
		$res =$dbUtil->executeDQL($sql,MYSQLI_NUM);
		// print_r($res);
		$arr_case_v_id=array();
		foreach ($res as $key => $value) {
			$arr_case_v_id[]=$value[2];
		}
		
		//print_r($arr_case_v_id);
		$sql="SELECT tc_case_v.case_v_id AS 'case_v_id',count(tc_testcase.testcase_id) as 'Pass' 
		FROM tc_case_v 
		LEFT JOIN tc_testcase ON tc_case_v.case_v_id = tc_testcase.case_v_id 
		AND tc_testcase.graph='Pass' AND tc_testcase.sound='Pass' AND tc_testcase.feature='Pass'
		WHERE task_id = '".$task_id."' GROUP BY tc_case_v.case_v_id";     //修正连接不上的问题，将group by tc_tesecase.case_v_id 改为tc_case_v.case_v_id
		//print_r($sql);
		//exit();
		$res2=$dbUtil->executeDQL($sql,MYSQLI_ASSOC);
		//print_r($res);
		//print_r($res2);
		foreach ($res as $key => $value) {
			foreach ($res2 as $k => $v) {
				if($value[3]==$v['case_v_id']){

					// print_r($value);
					$res[$key][3]=$v['Pass'];
					$res[$key][7]=$res[$key][4];
					$res[$key][4]=$value[2]-$v['Pass'];
					// array_splice($res[$key], 4,$v['Pass']);
					// $value[7]=$v['Pass'];
				}
			}
		}
		//exit();
		return $res;
		// print_r($res);
	}

//---------------v2-----------------
}

