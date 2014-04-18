<?php
class Models_Excel_GameVersionDB{
	public function getAllGameVersion(){
		
	}
	
	/**
	 * 获取游戏的版本
	 * @param unknown_type $gameId
	 * @return multitype:multitype:unknown
	 */
	public function getGameVersionByGameId($gameId){
		$db = new DBTool_DB();
		$sql = "select * from tc_game_v where game_id = ?";
		$params = array("i",$gameId);
		$gameversions = $db->query($sql, $params);
		return $gameversions;
	}
	
	public function getGameVersionById($game_v_id){
		$db = new DBTool_DB();
		$sql = "select * from tc_game_v where game_v_id = ?";
		$params = array("i",$game_v_id);
		$gameversion = $db->query($sql, $params);
		return $gameversion;
	}
}