<?php
/**
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract 
{

    public function _initConfig() 
    {
        $config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set("config", $config);
    }

	public function _initLocalName() 
	{
		Yaf_Loader::getInstance()->registerLocalNamespace(array(
			'Base',
		));
	}

	
	public function _initView(Yaf_Dispatcher $dispatcher) 
	{	
		$config = new Yaf_Config_Ini(APP_PATH.'/conf/smarty.ini', 'product');
		$smarty = new Smarty_Adapter( APP_PATH . "/application/views/", $config->toArray());
		Yaf_Dispatcher::getInstance()->setView ( $smarty );
	}

}
    
