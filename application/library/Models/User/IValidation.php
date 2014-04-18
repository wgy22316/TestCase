<?php

interface Models_User_IValidation{
	

	/*检查输入参数是否是email，且是否含有sql注入
	 *没有  true  有false 
	 *
	*/
	public function checkEmail($email);
	
	/*特殊字符检验
	 *没有  true  有false 
	 *
	*/
	public function checkSpecialChar($char);

	/*sql注入检验
	 *没有  true  有false 
	 *
	*/
	public function checkSqlInject($sql);

	/*验证注册信息
	 *合法  null  不合法array
	 *
	*/
	public function checkRegUser($user);
	

	/*验证密码和重复密码是否一直
	 *一致  true  不一致false 
	 *
	*/
    public function checkPassandrpass($str1,$str2);

    /*必填字段不能为空
	 *没有空  true  有空false 
	 *
	*/
    public function checkMustField($user);

}
