     
<?php

/**
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract{
                /**
                 * 注册一个插件
                 * 插件的目录是在application_directory/plugins
                 */
        public function _initPlugin(Yaf_Dispatcher $dispatcher) {
                // printf("_initPlugin");
                $privilege = new PrivilegePlugin();
                $dispatcher->registerPlugin($privilege);
        }
}
  