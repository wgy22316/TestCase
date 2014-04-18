<?php

require_once  APP_PATH.'/application/modules/Excel/ExcelTool/ExportExcel.class.php';
require_once    APP_PATH.'/application/modules/Excel/ExcelTool/ImportExcel.class.php';

class exceloperaController extends Yaf_Controller_Abstract{
	
	//字符转码
	public function codeConver($str) {
	
		if(empty($str)){
			return ;
		}else{
			return iconv("utf-8", "gb2312", $str);
		}
	}

	//上传界面
	public function excelinputviewAction(){
		return true;
	}
	
	//获取游戏的版本
	public function getGameVersionAction(){
 		$gameName = $_POST['gameName'];
		$gameDB = new Models_Excel_GameDB;
		$versions = $gameDB->getGameVersionByName($gameName);
		echo json_encode($versions);
		return false;
	}
	
	//上传文件，并把上传文件数据导入数据库
	public function excelinputAction(){
		$gameDB = new Models_Excel_GameDB;
		$caseDB = new Models_Excel_CaseDB();
		$testcaseDB = new Models_Excel_TestCaseDB();
		$caseVersionDB = new Models_Excel_CaseVersionDB();
		$gameVersioDB = new Models_Excel_GameVersionDB();
		$importExcel = new ImportExcel();
		$user_id = Yaf_Session::getInstance()->get("user_id");
		$gameId = Yaf_Session::getInstance()->get("game_id");
		
		$targetFolder = '/upload';
		$verifyToken = md5('unique_salt' . $_POST['timestamp']);
		if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = APP_PATH. $targetFolder;
			$targetFile = rtrim($targetPath,'/') . '/' .$this->codeConver($_FILES['Filedata']['name']);
			$fileTypes = array('xls'); // File extensions
			$fileParts = pathinfo($_FILES['Filedata']['name']);   //返回  array(dirname  ,basename ,extension,filename)

			//判断上传文件是否是xls文件
			if (in_array($fileParts['extension'],$fileTypes)) {
				//从临时文件副本移到项目upload文件夹中
				move_uploaded_file($_FILES["Filedata"]["tmp_name"],APP_PATH ."/upload/" .$this->codeConver($_FILES["Filedata"]["name"]));
				//解析Excel
				$filePath = APP_PATH."/upload/".$this->codeConver($_FILES["Filedata"]["name"]);
				$arrFile = explode('.xls', $_FILES["Filedata"]["name"]);  //文件以‘.xls’分割 成数组，$arrFile[0]只包含文件的名字，不取文件的后缀
				$fileName = $arrFile[0];  //文件名
				$rowMsg = $importExcel->getRowNumByMark($filePath, "EOF");
				$columnMsg = $importExcel->getColumnNumByMark($filePath, "EOF");
				if(!is_numeric($rowMsg)){
					echo $rowMsg;
					return false;
				}
				if(!is_numeric($columnMsg)){
					echo $columnMsg;
					return false;
				}
				$errorMsg = $importExcel->checkTestCase($filePath);
				$excelData = $importExcel->readTestCase($filePath);
				if($excelData != null){
					$isExsitCase = $caseDB->isExsitcase($gameId,$fileName);   //案例是否存在
					if($isExsitCase == true){
						$caseId = $caseDB->getCaseIdByGameAndCasename($gameId, $fileName); 
						$maxCaseVersion= $caseVersionDB->getMaxCaseVersionByCaseId($caseId);
						$arrMaxCaseVersion = explode(".", $maxCaseVersion);
						$updateVersion = $arrMaxCaseVersion[1]+ 1;
						$newCaseVersion = $arrMaxCaseVersion[0].".".$updateVersion;
						
						$cratetime = date('Y-m-d H:i:s');
						$caseVersion = array('case_id'=>$caseId,'version'=>$newCaseVersion,'author_id'=>$user_id,'create_time'=>$cratetime);
						$caseVersionId = $caseVersionDB->addCaseVersion($caseVersion);  //添加$caseVersion,获取自增ID
						for($i = 0 ; $i < count($excelData) ; $i++){
							$testcaseDB->addTestCase($caseVersionId, $excelData[$i]);
						}
					}else{
						$caseId = $caseDB->addCase($fileName, $gameId); //添加case,获取自增ID
						$cratetime = date('Y-m-d H:i:s');
						$caseVersion = array('case_id'=>$caseId,'version'=>'V1.0','author_id'=>$user_id,'create_time'=>$cratetime);
						
						$caseVersionId = $caseVersionDB->addCaseVersion($caseVersion);  //添加$caseVersion,获取自增ID
						for($i = 0 ; $i < count($excelData) ; $i++){
							$testcaseDB->addTestCase($caseVersionId, $excelData[$i]);
						}
					}
				}
				$this->deleteFile($filePath);
				if($errorMsg != "true"){
					echo $errorMsg;
				}
				return false;
			}
		}
	}
	
	public function checkFileExsitAction(){
		$caseDB = new Models_Excel_CaseDB();
		$gameId = $_POST['game_id'];
		$filename = explode('.xls', $_FILES["Filedata"]["name"]);  //文件以‘.xls’分割 成数组，$filename[0]只包含文件的名字，不取文件的后缀
		$isExsitCase = $caseDB->isExsitcase($gameId,$filename[0]);   //案例是否存在
		if($isExsitCase == true){
			echo 1;   //存在
		}else{
			echo 0;   //不存在
		}
	}
	
	public function exportexcelAction(){
		
		$exportExcel = new ExportExcel();
		$testcaseDB = new Models_Excel_TestCaseDB();
		$caseVersionId = $_POST['case_v_id'];
		//echo $caseVersionId;
		//file_put_contents("SQL.log", "case_v_idExcel：".$caseVersionId."\r\n",FILE_APPEND);
		$data = $testcaseDB->getTestcaseNameByVersionId($caseVersionId);
		//casename 和version构成文件名
		$caseName = $data[0]['casename'].$data[0]['version'];
		echo $caseName;
		//获取testcase数据
		$testcases = $testcaseDB->getTestCaseByCaseVersionId($caseVersionId);
		//print_r($testcases);
   		$objExcel = $exportExcel->writeBasicTestCase(null, $caseName);
   		$exportExcel->writeBodyTestCase($objExcel,$testcases, "download",$caseName);
 		return false;
	}
	
	public function exportviewAction(){
		
		$gameId= $_GET['game_id'];
		$caseDB = new Models_Excel_CaseDB();
		$cases = $caseDB->getAllCaseByGameId($gameId);
		//print_r($cases);
		$this->getView()->assign(array('cases'=>$cases));
	}
	
	public function getCaseVersionAction(){
		
		$caseVersionDB = new Models_Excel_CaseVersionDB();
		$caseId = $_POST['case_id'];
		$caseVersions = $caseVersionDB->getCaseVersionByCaseId($caseId);
 		echo json_encode($caseVersions);
 		return false;
	}
	
	//删除服务器端的文件
	public function deleteFile($filepath){
		unlink($filepath);
	}
	
	
}