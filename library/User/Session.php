<?php
@session_start();

/**
 * 用户登录-session存储
 * @autor yuanxch
 * @date 2012-8-6
 */
class User_Session
{

    /**
     * @var 用户id
     */
	private $__uid = false;
    /**
     * @var 用户帐号
     */
	private $__uname = false;
    /**
     * @var 用户id session key值
     */
	private $__uidTag = 'uid';
    /**
     * @var 用户帐号 session key值
     */
	private $__unameTag = 'uname';

	/**
	 * @desc 初始用户数据
	 */
	public function __construct()
	{
		if( isset( $_SESSION[$this->__uidTag] ) )
		{
			$this->__uid  = $_SESSION[$this->__uidTag];
			$this->__uname = $_SESSION[$this->__unameTag];
		}
	}


	/**
	 * @desc 本地用户登录
	 * @param array 数据
	 * @return boolean
	 */
	public function login( $data )
	{
		@list( $uid, $uname ) = $data;
        print_r($uid);
        $_SESSION[$this->__uidTag] = $uid;
        $_SESSION[$this->__unameTag] = $uname;
	}


	/**
	 * @desc 登出
	 */
	public function logout()
	{
        unset( $_SESSION[$this->__uidTag] );
        unset( $_SESSION[$this->__unameTag] );
	}

	/**
	 * @desc 用户ID
	 * @return int
	 */
	public function getUid()
	{
		return $this->__uid;
	}

	/**
	 * @desc 用户名
	 * @return string
	 */
	public function getUname()
	{
		return $this->__uname;
	}
}


