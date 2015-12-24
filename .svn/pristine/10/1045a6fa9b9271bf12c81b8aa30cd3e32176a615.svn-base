<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Original Author <449127727@qq.com>                        |
// |          yuanxch <449127727@qq.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$


/**
 * Smarty视图适配类
 *
 */
class Smarty_Adapter implements Yaf_View_Interface 
{
    /**
     * @var Array 脚本
     */
    var $objects = array();

    /**
     * Smarty object
     *
     * @var Smarty
     */
    public $_smarty;
    private $_template_dir = '';

    /**
     * Constructor
     *
     * @param string $template_dir
     * @param array $param
     * @return void
     */
    public function __construct($template_dir = null, $param = array()) 
    {
        $this->_smarty = new Smarty();        
        if (null !== $template_dir) {
            $this->setScriptPath($template_dir);
            $this->_template_dir = $template_dir;
        }

        if (is_array($param)) {
            foreach ($param as $key => $value) {
                $this->_smarty->$key = $value;
            }
        }
	//用来合并js,css
        //$this->_smarty->loadFilter('post', 'script');
    }
    /**
     * Set the path to the templates
     *
     * @param string $path
     * @return void
     */
    public function setScriptPath($path) {
        if (is_readable($path)) {
            $this->_smarty->template_dir = $path;
            return;
        } 
        throw new Exception('Invalid path provided:' . $path);
    }
    /**
     * Retrieve the current template directory
     *
     * @return string
     */
    public function getScriptPath() {
        return $this->_smarty->template_dir;
    }
    /**
     * Alias for setScriptPath
     *
     * @param string $path
     * @param string $prefix
     *              Unused
     * @return void
     */
    public function setBasePath($path, $prefix = '') {
        return $this->setScriptPath($path);
    }
    /**
     * Assign a variable to the template
     *
     * @param string $key
     *              The variable name.
     * @param mixed $val
     *              The variable value.
     * @return void
     */
    public function __set($key, $val) {
        $this->_smarty->assign($key, $val);
    }
    /**
     * Allows testing with empty() and isset() to work
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key) {
        return (null !== $this->_smarty->get_template_vars($key));
    }
    /**
     * Allows unset() on object properties to work
     *
     * @param string $key
     * @return void
     */
    public function __unset($key) {
        $this->_smarty->clearAssign($key);
    }
    /*
     * (non-PHPdoc) @see Yaf_View_Interface::assign()
    */
    public function assign($name, $value = null) {
        $this->_smarty->assign($name, $value);
    }
    /*
     * (non-PHPdoc) @see Yaf_View_Interface::assign()
    */
    public function append($name, $value, $merge = false) {
        $this->_smarty->append($name, $value, $merge);
    }
    /**
     * Short description.
     *
     * Detail description
     * @param      none
     * @access     public
     * @return     void
     * @update     date time
     */
    function getTemplateVars($vars) {
        return $this->_smarty->getTemplateVars($vars);
    } // end func
    /*
     * (non-PHPdoc) @see Yaf_View_Interface::render()
    */
    public function render($name, $value = NULL) {
        //@todo 这里在测试环境下改为输出变量值
        if (isset($_GET['hellosmarty'])) {
            $this->displayVars();
        }
        $name = $this->stripIndex($name);
        if ($value) {
            $this->assign($value);
        }
        return $this->_smarty->fetch($name);
    }
    /**
     * 输出测试数据到页面
     */
    private function displayVars() {
        ob_start();
        foreach ($this->_smarty->tpl_vars as $key => $val) {
            echo '$' . $key . ' = ';
            print_r($val->value);
            echo "\n";
        }
        $str = ob_get_contents();
        ob_clean();
        $str = str_replace(' ', '&nbsp;', $str);
        header('Content-Type: text/html;charset=UTF-8');
        echo nl2br($str);
        exit;
    }
    /*
     * (non-PHPdoc) @see Yaf_View_Interface::display()
    */
    public function display($name, $value = NULL) {
        if ($value) {
            $this->assign($value);
        }
        //@todo 这里在测试环境下改为输出变量值
        if (isset($_GET['hellosmarty'])) {
            $this->displayVars();
        }
        $name = $this->stripIndex($name);
        $this->_smarty->display($name);
    }
    public function fetch($name) {
        return $this->_smarty->fetch($name);
    }
    /**
     * 去掉index开头的默认控制器目录
     * @param string $name
     * @return string
     */
    private function stripIndex($name) {
        if (substr($name, 0, 1) == '/') {
            $name = substr($name, 1);
            $this->setScriptPath(dirname($this->_template_dir)); //设定了/说明要到smarty模板的根目录下 这里通过dirname回到上一级既根目录
            
        } elseif (substr($name, 0, 6) == 'index/') { //yaf会传递的路径包含控制器，但是我们采用的app的模块开发形式，因此忽略默认控制器
            $name = substr($name, 6);
        }
        return $name;
    }
}

