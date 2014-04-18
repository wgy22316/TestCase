<?php
/**
 * @name IndexController
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends Yaf_Controller_Abstract {

	public function indexAction() {
		$this->redirect('/TestCase/public/login/Login/login');
		return false;
	}
}

