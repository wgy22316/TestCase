<?php
class Models_User_Note{
	private $_username;
	private $_title;
	private $_content;
	private $_notetime;
	private $_remarks;
	
	public function Models_User_Note($username,$title,$content,$notetime,$remarks){
		$this->setUsername($username);
		$this->setTitle($title);
		$this->setContent($content);
		$this->setNotetime($notetime);
		$this->setRemarks($remarks);
	}
	/**
	 * @return the $_title
	 */
	public function getTitle() {
		return $this->_title;
	}

	/**
	 * @return the $_content
	 */
	public function getContent() {
		return $this->_content;
	}

	/**
	 * @return the $_notetime
	 */
	public function getNotetime() {
		return $this->_notetime;
	}

	/**
	 * @return the $_remarks
	 */
	public function getRemarks() {
		return $this->_remarks;
	}

	/**
	 * @param field_type $_title
	 */
	public function setTitle($_title) {
		$this->_title = $_title;
	}

	/**
	 * @param field_type $_content
	 */
	public function setContent($_content) {
		$this->_content = $_content;
	}

	/**
	 * @param field_type $_notetime
	 */
	public function setNotetime($_notetime) {
		$this->_notetime = $_notetime;
	}

	/**
	 * @param field_type $_remarks
	 */
	public function setRemarks($_remarks) {
		$this->_remarks = $_remarks;
	}
	/**
	 * @return the $_username
	 */
	public function getUsername() {
		return $this->_username;
	}

	/**
	 * @param field_type $_username
	 */
	public function setUsername($_username) {
		$this->_username = $_username;
	}


	
	
}