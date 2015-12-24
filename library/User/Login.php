<?php

/**
 * Class User_Login
 * @author yuanxch
 *
 */
class User_Login
{


	/**
	 * 用户登录
	 * @param string $username
	 * @param string $password 
	 * @return int <0:失败,>0:uid
	 */
	public function login($username, $password)
	{
		$dao = new DB_Xdf_User();
		$user = $dao -> scalar("id, salt, password", "phone='{$username}'");

		if(!$user) {
			return -1;
		}

		if($user['password'] != md5($user['salt'] . "|" . $password)) {
			return -2;
		}

		return $user['id'];		
	}



}