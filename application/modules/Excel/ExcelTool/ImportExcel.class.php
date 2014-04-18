<?php
require_once APP_PATH.'/application/modules/Excel/ExcelTool/Classes/PHPExcel.php';
require_once APP_PATH.'/application/modules/Excel/ExcelTool/Classes/PHPExcel/IOFactory.php';
require_once APP_PATH.'/application/modules/Excel/ExcelTool/Classes/PHPExcel/Reader/Excel5.php';
require_once APP_PATH.'/application/modules/Excel/ExcelTool/IImportExcel.php';

class ImportExcel implements IImportExcel{
	
	//字符转码
	 public function codeConver($str) {
	 	if(empty($str)){
	 		return ;
	 	}else{
	 		return iconv("utf-8", "gb2312", $str);
	 	}
	 }

	//读取测试用例	
	 public function readTestCase($filePath) {
	 	$objReader = PHPExcel_IOFactory::createReader('Excel5');//使用2003表格
	 	$objPHPExcel = $objReader->load($filePath); //$filename可以是上传的文件，或者是指定的文件
	 	$sheet = $objPHPExcel->getSheetByName('Test case');  //TestCase工作薄
	 	
// 	 	$highestRow = $sheet->getHighestRow(); // 取得总行数
// 	 	$highestColumn = $sheet->getHighestColumn();//取得最大列数的名字
// 	 	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//返回最大列数的索引
		
	 	$startNum =  $this->getRowNumByMark($filePath, "TC ID") + 2;
	 	$highestRowNum = $this->getRowNumByMark($filePath, "EOF");
	 	$highestColumnNum = $this->getColumnNumByMark($filePath, "EOF");
	 	//循环读取Excel TestCase 工作薄中的数据 ,列数写死
	 	$r = 0;
	 	$flag = false;
	 	$data=array();
	 	for($i = $startNum ; $i < $highestRowNum ; $i++){
	 		for($j = 0; $j < $highestColumnNum; $j++){
	 			if($j == 0){
	 				$TCIDValue= $sheet->getCellByColumnAndRow($j,$i)->getFormattedValue();
	 				$titleValue= $sheet->getCellByColumnAndRow($j+1,$i)->getFormattedValue();
	 				if(empty($TCIDValue)||empty($titleValue)){
	 					$flag = true;
	 					break;
	 				}
	 				
	 				$arrTCIDValue = explode(":", $TCIDValue);
	 				$tc_id = $arrTCIDValue[1];  //取出TC_ID中的数字
	 				if($tc_id != null){
	 					$data[$r][] = $tc_id;
	 				}
	 				
	 			}else{
	 				$flag = false;
	 				$cellValue= $sheet->getCellByColumnAndRow($j,$i)->getFormattedValue();  //取出正确的格式
	 				//判断其中是否为数学公式
	 				if(strstr($cellValue, "=")){
	 					$cellValue =$sheet->getCellByColumnAndRow($j,$i)->getCalculatedValue();
	 				}
	 				if($cellValue == null){
	 					$cellValue = null;
	 				}
	 				$data[$r][] =$cellValue;
	 			}
	 		}
	 		if($flag != true){
	 			$r++;
	 		}
	 	}
	 	return $data;
	 }
	 
	 public function checkTestCase($filePath){
	 	$objReader = PHPExcel_IOFactory::createReader('Excel5');//使用2003表格
	 	$objPHPExcel = $objReader->load($filePath); //$filename可以是上传的文件，或者是指定的文件
	 	$sheet = $objPHPExcel->getSheetByName('Test case');  //TestCase工作薄
	 	 
// 	 	$highestRow = $sheet->getHighestRow(); // 取得总行数
// 	 	$highestColumn = $sheet->getHighestColumn();//取得最大列数的名字
// 	 	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//返回最大列数的索引
	 	
	 	$startNum =  $this->getRowNumByMark($filePath, "TC ID") + 2;
	 	$highestRowNum = $this->getRowNumByMark($filePath, "EOF");
	 	$highestColumnNum = $this->getColumnNumByMark($filePath, "EOF");
	 	//循环读取Excel TestCase 工作薄中的数据 ,列数写死
	 	$row = 0;
	 	$msg = "";
	 	for($i = $startNum ; $i < $highestRowNum ; $i++){
	 		for($j = 0; $j < $highestColumnNum ; $j++){
	 			if($j == 0){
	 				$TCIDValue= $sheet->getCellByColumnAndRow($j,$i)->getFormattedValue();
	 				$titleValue= $sheet->getCellByColumnAndRow($j+1,$i)->getFormattedValue();
	 				if(empty($TCIDValue)&&!empty($titleValue)){
 						//$msg=$msg.$i."行数据不合法,TCID为空     ";
	 					$msg=$msg.$i.",";
 						$row++;
	 					break;
	 				}
	 				if(!empty($TCIDValue)&&empty($titleValue)){
	 					//$msg=$msg.$i."行数据Title为空,应该删除该行   ";
	 					$msg=$msg.$i.",";
	 					$row++;
	 					break;
	 				}
	 			}
	 			break;
	 		}
	 		
	 	}
	 	if($row != 0){
	 		$msg="有".$row."行数据不合法</br>".$msg."行数据不合法";
	 	}else{
	 		$msg = "true";
	 	}
	 	return $msg;
	 }
	
	
	 //通过标记字符串获取行号
	 public function getRowNumByMark($filePath,$string){
	 	$objReader = PHPExcel_IOFactory::createReader('Excel5');//使用2003表格
	 	$objPHPExcel = $objReader->load($filePath); //$filename可以是上传的文件，或者是指定的文件
	 	$sheet = $objPHPExcel->getSheetByName('Test case');  //TestCase工作薄
	 	$highestRow = $sheet->getHighestRow(); // 取得总行数
	 	$highestColumn = $sheet->getHighestColumn();  
	 	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//返回最大列数的索引
	 	for($i = 1 ;$i <=$highestRow ; $i++){
	 		for($j = 0 ; $j < $highestColumnIndex ; $j++){
 				$value= $sheet->getCellByColumnAndRow($j,$i)->getFormattedValue();
 				$value = strtoupper($value);
 				if($value == $string){
 					return $i;
 				}
	 			break;
	 		}
	 	}
	 	$msg = "文件行不存在'EOF',禁止上传";
	 	return $msg;
	 }
	 
	
	 //通过标记字符串获取列号
	 public function getColumnNumByMark($filePath,$string){
	 	$objReader = PHPExcel_IOFactory::createReader('Excel5');//使用2003表格
	 	$objPHPExcel = $objReader->load($filePath); //$filename可以是上传的文件，或者是指定的文件
	 	$sheet = $objPHPExcel->getSheetByName('Test case');  //TestCase工作薄
	 	//$highestRow = $sheet->getHighestRow(); // 取得总行数
	 	$highestRow = $this->getRowNumByMark($filePath, "EOF"); // 取得总行数
	 	$highestColumn = $sheet->getHighestColumn();
	 	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//返回最大列数的索引
	 	for($i = 1 ;$i < $highestRow ; $i++){
	 		for($j = 0 ; $j < $highestColumnIndex ; $j++){
	 			$value= $sheet->getCellByColumnAndRow($j,$i)->getFormattedValue();
	 			$value = strtoupper($value);
	 			if($value == $string){
	 				return $j;
	 			}
	 		}
	 	}
	 	$msg = "文件列不存在'EOF',禁止上传";
	 	return $msg;
	 }
	
	 //读取测试相关信息
	 public function getBasicdataByTestCase($filename){
	 	//$filename = "E:/OP_boss之家-szq.xls";
	 	$objReader = PHPExcel_IOFactory::createReader('Excel5');//使用2003表格
	 	$objPHPExcel = $objReader->load($filename); //$filename可以是上传的文件，或者是指定的文件
	 	$sheet = $objPHPExcel->getSheetByName('Test case');  //TestCase工作薄
	 	 
	 	//读取测试相关信息
	 	$project = $sheet->getCell("B1")->getValue();
	 	
	 	$build = $sheet->getCell("B2")->getValue();
	 
	 	
	 	$sku = $sheet->getCell("B3")->getValue();
	
	 	
	 	$validated = $sheet->getCell("C5","D5")->getValue();
	 
	 	
	 	$level= $sheet->getCell("C6","D6")->getValue();
	
	 	
	 	$planName = $sheet->getCell("C7","D7")->getValue();
	 
	 	
	 	$Tester = $sheet->getCell("C8","D8")->getValue();
	
	 	 
	 	$basicdata = Array($Tester,$planName,$level,$validated,$project,$build,$sku);
	 	return $basicdata;
	 }
}