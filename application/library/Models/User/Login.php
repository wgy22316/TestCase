<?php


class Models_User_Login
{
       private $_username; //登陆用户名
       private $_password; //登陆密码
       private $_id;//登陆ID

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
       * @return the $_id
       */
       public function getId() {
             return $this->_id ;
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
       * @param field_type $_id
       */
       public function setId($_id) {
            $this-> _id = $_id;
      }

}
