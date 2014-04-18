<?php


interface IImportExcel {
	/**
	 * 对字符进行转码
	 * @param unknown_type $Str
	 */
	public function codeConver($str);
	
	/**
	 * 对TestCase 工作薄进行导入
	 * @param unknown_type $filename
	 */
	public function readTestCase($filePath);
	
	/**
	 * 对TestCase 工作薄中基本信息进行导入
	 * @param unknown_type $filename
	 */
	public function getBasicdataByTestCase($filename);
	
	/**
	 * 检查TestCase是否符合格式
	 * @param unknown_type $filename
	 * @param unknown_type $data
	 */
	public function checkTestCase($filePath);
	
	/**
	 * 通过标记字符串获取行号
	 * @param unknown_type $string
	 */
	public function getRowNumByMark($filePath,$string);
	
	/**
	 * 通过标记字符串获取列号
	 */
	public function getColumnNumByMark($filePath,$string);
}