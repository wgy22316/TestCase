// <?php
// // include 'Models_User_IMenu.php';

// class Models_User_Menumodel implements Models_User_IMenu{
// // 	/* (non-PHPdoc)
// // 	 * @see IMenu::findAllMenu()
// // 	 */
// // 	private $_menutable = "tc_menu";
// // 	public $i=0;
// // 	public function findAllMenu() {
// // 		$instance = DBTool_ConnectDB::getInstance();
// // 		$sql = "select * from ".$this->getMenutable()." ; ";
// // 		$stmt = $instance->conn->query($sql);
// // // 		$stmt->execute();
// // // 		$i=0;
// // // 		while($stmt->next_result()){
// // 		$array = array();
// // 		if($stmt){
// // 			while(($row=$stmt->fetch_array(MYSQLI_ASSOC))!=false){
// // 				//这种写法，数组会自动添加内容
// // 				$array[] = $row;
// // 			}
// // 				$stmt->close();
// // 				return $array;
// // 		}
// // 		$stmt->close();
// // 		return null;
// // 	}
// // 	/**
// // 	 * @return the $_menutable
// // 	 */
// // 	public function getMenutable() {
// // 		return $this->_menutable;
// // 	}

// // 	/**
// // 	 * @param string $_menutable
// // 	 */
// // 	public function setMenutable($_menutable) {
// // 		$this->_menutable = $_menutable;
// // 	}


	
// }