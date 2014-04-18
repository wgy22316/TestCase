<?php
interface IExportExcel{
	/**
	 * 导出测试主体表格信息
	 * @param unknown_type $filename文件名
	 * @param $basicdata 基础数据
	 */
	public function writeBasicTestCase($basicdata,$filename);
	
	/**
	 * 导出测试基本信息（测试人，测试项目名等）
	 * @param unknown_type $filename文件名
	 * @param $objActSheet Excel对象
	 * @param $selectType 用户是在浏览器预览还是选择下载
	 * @param 下载文件名
	 */
	public function writeBodyTestCase($objExcel,$data,$selectType,$filename);

	
}