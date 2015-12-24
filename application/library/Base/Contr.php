<?php
use Symfony\Component\HttpFoundation\Request;

class Base_Contr extends Yaf_Controller_Abstract
{
    /**
     * @var boolean 需要登录
     */
    public $needLogin = true;

    /**
     * @var platform 平台
     */
    public $platform = 'web';

    /**
     * @var boolean wap访问
     */
    public $wap = false;

    /**
     * @var boolean 是否是手机访问
     */
    public $isMobile = false;

    /**
     *
     * @var bool 当前用户uid
     */
    protected $uid = false;

    /**
     *
     * @var bool 当前最新频道school_id
     */
    protected $school_id = false ;
    /**
     * @var ajax请求
     */
    protected $ajax = false;

    /**
     * @var boolean 是否打开debug
     */
    protected $debug = false;


	/**
	 * Controller的init方法会被自动首先调用
	 */
	public function init() 
	{
        //调试mysql
        if (isset($_GET['debug6429360'])) {
            $this->debug = true;
            DB_Mysqli::$DEBUG=1;
        }

        $this->platform = isset($_REQUEST['platform']) ? $_REQUEST['platform'] : $this->platform;
        Yaf_Registry::set("platform", $this->platform);
        $this->isMobile = $this->platform == 'ios' || $this->platform == 'android';
        $this->wap = !$this->isMobile && Common_Mobile::isMobile();
        $this->uid = $this->get('uid', $this->post('uid', false));
        $this->controllerName = $this->getRequest()->getControllerName();
        $this->actionName = $this->getRequest()->getActionName();

        $userModel = new UserModel();
/*        if (!isset(Common_Config::$NotNeedLogin[$this->controllerName][$this->actionName])
            && $this->isMobile 
            && $this->needLogin 
            && !$this->debug) 
        {
            $token = $this->get("token", $this->post("token", false));
            if (!$token) {
                $this->redirect(NULL, Common_Error::ERROR_TOKEN_NOT_EXISTS);
            }

            $api = $this->getRequestApi();
            $check = Common_Token::check($token, $this->uid, strtolower($api));
            if (!$check) {          
                $this->redirect(NULL, Common_Error::ERROR_TOKEN);
            }
        }*/
        $this->uid = $this->uid ? $this->uid : $userModel->getUid();


		/**
		 * 如果是Ajax请求, 则关闭HTML输出
		 */
		if ($this->getRequest()->isXmlHttpRequest() || 'POST' == $this->getRequest()->getMethod()) {
            $this->ajax = true;
			Yaf_Dispatcher::getInstance()->disableView();			
		} else {
			//$path = $this->getView()->getScriptPath();
			//$path[0] = $path[0] . "/" . strtolower($this->getRequest()->getControllerName());
			//$this->getView()->setScriptPath($path[0]);
		}
        
        //print_r($this->uid?$this->uid:"null");
        //验证登录
        /*if (
            !isset(Common_Config::$NotNeedLogin[$this->controllerName][$this->actionName]) 
            && $this->needLogin 
            && !$this->uid) {
            $this->redirect("/user/login?from=" . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ""), Common_Error::ERROR_USER_NOT_LOGIN);

            $this->redirect("/user/verify", Common_Error::ERROR_USER_NOT_LOGIN);
        }*/

        //用户信息
/*        if($this->uid) {
            $user = $userModel->getUser($this->uid);
            $this->assign("uInfo", $user);
            Yaf_Registry::set("uid", $this->uid);
        }*/

	}

    /**
     * 内部重定向
     */
    function redirect($url, $errno = Common_Error::ERROR_FAIL)
    {
        if ($this->ajax || $this->isMobile == true) {
            $this->displayJson($errno);
        }

        header("Location:$url");
        exit;
    }

	function assign( $k, $v )
	{
		$this->getView()->assign( $k, $v );
	}


    function post($name = NULL, $default_val = NULL)
    {
        $req = new Request();
        $req = $req->createFromGlobals();
        if ($name) {
            return $req->request->get($name, $default_val);
        }
        return $req->request;
    }

    function get($name = NULL, $default_val = NULL)
    {
        $req = $this->getRequest();
        $val = $req -> getParam($name, false);
        
        if (false === $val) {
            $req = new Request();
            $query = $req->createFromGlobals()->query;
            $val = $query->get($name, $default_val);
        }
        return $val;
    }

    
    function getRequestApi()
    {        
        return $this->getRequest() -> getControllerName() . "/" . $this->getRequest() -> getActionName();
    }




	/**
	 * 输出json
	 * @param int $errno 
	 * @param unknown $data
	 * @param string $msg
	 * @return void
	 */
	function displayJson( $errno, $data = NULL, $error = NULL )
	{
        header('Content-Type:application/json;charset=UTF-8');
		$error = $error ? $error : (isset(Common_Error::$errmsg[$errno]) ? Common_Error::$errmsg[$errno] : '');
		$return = array('errno' => $errno, 'error' => $error, 'data'  => $data);
        Common_Log::debug(json_encode($return));
		exit( json_encode( $return, JSON_NUMERIC_CHECK ) );
	}

    function displayJsonUdo( $code, $data = NULL, $msg = NULL )
    {
        header('Content-Type:application/json;charset=UTF-8');
        $msg = $msg ? $msg : (isset(Common_Error::$errmsg[$code]) ? Common_Error::$errmsg[$code] : '');
        $return = array('code' => $code, 'msg' => $msg, 'data'  => $data);
        Common_Log::debug(json_encode($return));
        exit( json_encode( $return, JSON_NUMERIC_CHECK ) );
    }


	function displayMathML($mathml) {
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN" "http://www.w3.org/TR/MathML2/dtd/xhtml-math11-f.dtd"> 
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<title></title>
			<link rel="stylesheet" href="/css/mathml.css" />
			</head>
			<body>';
		echo $mathml;
		echo '</body>';
		echo '</html>';
		exit;
	}

    function showMsg($msg)
    {
        header('Content-Type:application/json;charset=UTF-8');
        exit($msg);
    }

    function show($template)
    {
        $this->getView()->display($template);
    }



	

}
