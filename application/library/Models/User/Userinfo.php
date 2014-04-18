<?php

class Models_User_Userinfo{

		private $_user_id;//登陆用户名
		private $_username; //用户名称
		private $_password; //登陆密码
		private $_rpassword; //重复密码
		private $_email;//注册email
		
		public function UserInfo($_user_id,$_username,$_password,$_rpassword,$_email){
			$this-> _user_id = $_user_id;
			$this-> _username = $_username;
			$this-> _password = $_password;
			$this-> _rpassword = $_rpassword;
			$this-> _email = $_email;
			
		}
		/**
		 * @return the $_username
		 */
		public function getUsername() {
			return $this->_username ;
		}
		/**
		 * @return the $_password
		 */
		public function getPassword() {
			return $this->_password ;
		}
	
		/**
		 * @return the $_rpassword
		 */
		public function getRpassword() {
			return $this->_rpassword ;
		}
		/**
		 * @return the $_user_id
		 */
		public function getUser_id() {
			return $this->_user_id ;
		}
		/**
		 * @return the $_email
		 */
		public function getEmail() {
			return $this->_email;
		}


		/**
		 * @param field_type $_username
		 */
		public function setUsername($_username) {
			$this-> _username = $_username;
		}
	
		/**
		 * @param field_type $_password
		 */
		public function setPassword($_password) {
			$this-> _password = $_password;
		}
	
		/**
		 * @param field_type $_rpassword
		 */
		public function setRpassword($_rpassword) {
			$this-> _rpassword = $_rpassword;
		}
	
		/**
		 * @param field_type $_user_id
		 */
		public function setUser_id($_user_id) {
			$this-> _user_id = $_user_id;
		}
	
		/**
		 * @param field_type $_email
		 */
		public function setEmail($_email) {
			$this-> _email = $_email;
		}
	
	
}