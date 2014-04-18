<?php
require_once APP_PATH.'/application/modules/Excel/ExcelTool/Classes/PHPExcel.php';
require_once APP_PATH.'/application/modules/Excel/ExcelTool/Classes/PHPExcel/IOFactory.php';
require_once APP_PATH.'/application/modules/Excel/ExcelTool/Classes/PHPExcel/Reader/Excel5.php';
require_once APP_PATH.'/application/modules/Excel/ExcelTool/IExportExcel.php';

class ExportExcel implements IExportExcel{
	
	 public function writeBasicTestCase($basicdata,$filename) {
	 	//创建Excel对象
	 	$objExcel = new PHPExcel();
	 	
	 	$objExcel->setActiveSheetIndex(0);   //设置当前sheet索引，sheet默认0
	 	$objActSheet = $objExcel->getActiveSheet();  //获取当前sheet对象
	 	$objActSheet->setTitle('Test case');  //设置sheet名字
	 	
	 	//设置表头
	 	$objActSheet->setCellValue("A1","Project:");
	 	$objActSheet->setCellValue("A2","Build:");
	 	$objActSheet->setCellValue("A3","SKU:");
	 	
	 	//合并单元格并填值
	 	$objActSheet->mergeCells("A5:B5");
	 	$objActSheet->setCellValue("A5","Validated by QA");
	 	$objActSheet->mergeCells("A6:B6");
	 	$objActSheet->setCellValue("A6","Execution level:");
	 	$objActSheet->mergeCells("A7:B7");
	 	$objActSheet->setCellValue("A7","Test Plan name:");
	 	$objActSheet->mergeCells("A8:B8");
	 	$objActSheet->setCellValue("A8","Tester(s) assigned:");
	 	
		//填值
	 	$objActSheet->setCellValue("B1",$basicdata[0]);
	 	$objActSheet->setCellValue("B2",$basicdata[1]);
	 	$objActSheet->setCellValue("B3",$basicdata[2]);
	 	
	 	//合并单元格
	 	$objActSheet->mergeCells("C5:D5");
	 	$objActSheet->setCellValue("C5",$basicdata[3]);
	 	
	 	$objActSheet->mergeCells("C6:D6");
	 	$objActSheet->setCellValue("C6",$basicdata[4]);
	 	
	 	$objActSheet->mergeCells("C7:D7");
	 	$objActSheet->setCellValue("C7",$basicdata[5]);
	 	
	 	$objActSheet->mergeCells("C8:D8");
	 	$objActSheet->setCellValue("C8",$basicdata[6]);
	 	
	 	//设置表格样式
	 	$styleArray = array(
	 			'font' => array(
	 					'bold' => true,
	 			),
	 			'alignment' => array(
	 					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
	 					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	 			),
	 			'borders' => array(
	 					'allborders' => array(
	 							'style' => PHPExcel_Style_Border::BORDER_THIN,
	 					),
	 			),
	 			'fill' => array(
	 					'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
	 					'rotation' => 90,
	 					'startcolor' => array(
	 							'argb' => 'FFA0A0A0',
	 					),
	 					'endcolor' => array(
	 							'argb' => 'FFFFFFFF',
	 					),
	 			),
	 	);
	 	//执行样式
	 	$objActSheet->getStyle('A1:B3')->applyFromArray($styleArray);
	 	$objActSheet->getStyle('A5:D8')->applyFromArray($styleArray);
	 	return $objExcel;
	 }

	
	 public function writeBodyTestCase($objExcel,$data,$selectType,$filename) {
	 	$objActSheet = $objExcel->getSheetByName('Test case'); //获取操作的工作薄
	 	
	 	//合并单元格，并填充表头
	 	$objActSheet->mergeCells("A10:A11");
	 	$objActSheet->setCellValue("A10","TC ID");
	 	
	 	$objActSheet->mergeCells("B10:B11");
	 	$objActSheet->setCellValue("B10","Title");
	 	
	 	$objActSheet->mergeCells("C10:C11");
	 	$objActSheet->setCellValue("C10","Initial Condition");
	 	
	 	$objActSheet->mergeCells("D10:D11");
	 	$objActSheet->setCellValue("D10","Procedure");
	 	
	 	$objActSheet->mergeCells("E10:E11");
	 	$objActSheet->setCellValue("E10","Expected Result");
	 	
	 	$objActSheet->mergeCells("F10:H10");
	 	$objActSheet->setCellValue("F10","Test Result");
	 	
	 	$objActSheet->setCellValue("F11","画面");
	 	$objActSheet->setCellValue("G11","生效");
	 	$objActSheet->setCellValue("H11","功能");
	 	
	 	$objActSheet->mergeCells("I10:I11");
	 	$objActSheet->setCellValue("I10","Total Test duration");
	 	
	 	$objActSheet->mergeCells("J10:J11");
	 	$objActSheet->setCellValue("J10","BUG ID");
	 	
	 	//添加EOF
	 	$objActSheet->setCellValue("K10","EOF");
	 	
	 	//表头样式
	 	$thStyle = array(
	 			'font' => array(
	 					'bold' => true,
	 			),
	 			'alignment' => array(
	 					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
	 					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	 			),
	 			'borders' => array(
	 					'allborders' => array(
	 							'style' => PHPExcel_Style_Border::BORDER_THICK,
	 					),
	 			),
	 			'fill' => array(
	 					'type' => PHPExcel_Style_Fill::FILL_SOLID,
	 					'startcolor' => array(
	 							'argb' => 'FFA0A0A0',
	 					),
	 					'endcolor' => array(
	 							'argb' => 'FFFFFFFF',
	 					),
	 			),
	 	);
	 	
	 	$bodyStyle = array(
	 			'font' => array(
	 					'bold' => false,
	 			),
	 			'alignment' => array(
	 					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
	 					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	 			),
	 			'borders' => array(
	 					'allborders' => array(
	 							'style' => PHPExcel_Style_Border::BORDER_THIN,
	 					),
	 			),
	 			
	 	);
	 	
	 	//执行样式
	 	$objActSheet->getStyle('A10:J11')->applyFromArray($thStyle);
	 	$maxRow = count($data)+11;
	 	$objActSheet->getStyle('A12:J'.$maxRow)->applyFromArray($bodyStyle);
	 	
	 	$objActSheet->fromArray($data,NULL,'A12'); //从A12开始填充值
	 	
	 	//填写EOF
	 	$objActSheet->setCellValue("A".($maxRow+1),"EOF");
	 	
	 	//设置列的宽度
	 	$objActSheet->getColumnDimension("A")->setWidth(9);
	 	$objActSheet->getColumnDimension('B')->setWidth(44);
	 	$objActSheet->getColumnDimension('C')->setWidth(14);
	 	$objActSheet->getColumnDimension('D')->setWidth(14);
	 	$objActSheet->getColumnDimension('E')->setWidth(37);
	 	$objActSheet->getColumnDimension('F')->setWidth(6);
	 	$objActSheet->getColumnDimension('G')->setWidth(6);
	 	$objActSheet->getColumnDimension('H')->setWidth(6);
	 	$objActSheet->getColumnDimension('I')->setWidth(13);
	 	$objActSheet->getColumnDimension('J')->setWidth(9);
	 	
	 	
	 	$outputFileName=$filename.".xls";
	
	 	$objExcel->getProperties()->setTitle("Test Case"); //设置文档标题
	 	//用户是预览还是下载
	 	if($selectType == "preview"){
	 		ob_end_clean();
	 		$objWriteHTML = new PHPExcel_Writer_HTML($objExcel); //输出网页格式的对象
	 		$objWriteHTML->save("php://output");   //输出到浏览器供用户预览
	 	}else if($selectType == "download"){
	 		ob_end_clean();
	 		Header('content-Type:application/vnd.ms-excel;charset=gb2312');
	 		header("Content-Type: application/force-download");
	 		header("Content-Type: application/octet-stream");
	 		header("Content-Type: application/download");
	 		header('Content-Disposition:inline;filename="'.$outputFileName.'"');
	 		header("Content-Transfer-Encoding: binary");
	 		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	 		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	 		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	 		header("Pragma: no-cache");
	 		$objWriter = new PHPExcel_Writer_Excel5($objExcel);//2003的流对象
	 		$objWriter->save('php://output');//参数-表示直接输出到浏览器，供客户端下载
	 	}
	 	
	 
	 }

	
}