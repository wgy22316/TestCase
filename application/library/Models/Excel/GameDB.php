<?php
class Models_Excel_GameDB{
	/**
	 * 查找所有游戏的信息
	 * @return multitype:unknown
	 */
	public function getAllGameName(){
		$db = new DBTool_DB();
		$sql = "select distinct gameName from tc_game";
		$params = null ;
		$data = $db->query($sql, $params);
		return $data;
	}
	
	
	public  function getGameIdByName($gameName){
		$db = new DBTool_DB();
		$msqli = $db->getConnt();
		$sql = "select game_id from tc_game where gamename = ?";
		$params = array("s",$gameName);
		$id = $db->query($sql, $params);
	    return $id[0]['game_id'];	
	}
	
	
}