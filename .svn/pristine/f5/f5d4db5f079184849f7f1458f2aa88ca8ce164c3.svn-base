<?php

final class Api_Token
{
	const KEY = "secret_key";
	const SIGN = "|";

	public static function getToken($uid, $api, $data)
	{
		return md5($uid . self::SIGN . $api . self::SIGN . self::KEY);
	}
}