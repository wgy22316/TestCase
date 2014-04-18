<?php

class Models_User_Validationmodel  implements Models_User_IValidation

{

  /** 
    *  静态成员变量 保存全局实例 
    *  @access private 
    */
      //邮箱正则
  	  private  $_EMAIL="/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
      //特殊字符正则
      private  $_SPECCIALCHAR= "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
      //sql正则
      private  $_SQL="/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|truncate|join|into|load_file|drop|count|outfile/";

      public static $instance=null;
      static private $_instance = NULL;  
  /** 
    * 私有化构造函数，防止外界实例化对象 
        */ 
    private function  __construct() {
    } 
    /** 
     *  私有化克隆函数，防止外界克隆对象 
     */ 
    private function  __clone(){
    	throw new Exception("Singleton Class Can Not Be Cloned");
    } 
    /** 
     *  静态方法, 单例统一访问入口 
     *  @return  object  返回对象的唯一实例 
     */ 
    static public function getInstance() { 
        if (is_null(self::$_instance) || !isset(self::$_instance)) { 
             self::$_instance = new Models_User_Validationmodel(); 
        } 
        return self::$_instance; 
    }

  //Email检验
  public function checkEmail($email){
     return  preg_match($this->getEMAIL(), $email)&&($this->checkSqlInject($email));
  }
  
  //特殊字符检验
  public function checkSpecialChar($char){
     return  !(preg_match($this->getSPECCIALCHAR(), $char));

  }

  //sql注入检验
  public function checkSqlInject($sql){
     return  !(preg_match($this->getSQL(), $sql));

  }

  //密码和重复密码
  public function checkPassandrpass($str1,$str2){
    return ($str1==$str2);

  }


  //必填字段不能为空
  public function checkMustField($user){
      return $user->getUser_id()==null||$user->getUsername()==null||$user->getEmail()==null||
              $user->getPassword()==null||$user->getRpassword();
  }

  public function checkRegUser($user){
    $result=null;
    if(!$this->checkMustField($user)){
      $result.="A required field cannot be empty;";
       return $result;
    }
    if(!$this->checkEmail($user->getEmail())){
      $result.="email Illegal;";
       return $result;
    }
    if(!$this->checkPassandrpass($user->getPassword(),$user->getRpassword())){
      $result.="The password and confirm password inconsistency;";
       return $result;
    }
    $string=$user->getUser_id().$user->getUsername().$user->getPassword().$user->getRpassword();

    if(!($this->checkSpecialChar($string))){
        $result.="Illegal special character input information;";
       return $result;
    }

    $string.=$user->getEmail();
    if(!($this->checkSqlInject($string))){
        $result.="Illegal SQL related content input information;";
        return $result;
    }
    return $result;
  }
/**
	 * @return the $_EMAIL
	 */
	public function getEMAIL() {
		return $this->_EMAIL;
	}


/**
	 * @return the $_SPECCIALCHAR
	 */
	public function getSPECCIALCHAR() {
		return $this->_SPECCIALCHAR;
	}

/**
	 * @return the $_SQL
	 */
	public function getSQL() {
		return $this->_SQL;
	}

/**
	 * @param string $_EMAIL
	 */
	public function setEMAIL($_EMAIL) {
		$this->_EMAIL = $_EMAIL;
	}

/**
	 * @param string $_SPECCIALCHAR
	 */
	public function setSPECCIALCHAR($_SPECCIALCHAR) {
		$this->_SPECCIALCHAR = $_SPECCIALCHAR;
	}

/**
	 * @param string $_SQL
	 */
	public function setSQL($_SQL) {
		$this->_SQL = $_SQL;
	}

  
	

} 


