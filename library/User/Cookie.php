<?php

/**
 * 用户登录-cookiee存储
 * @autor yuanxch
 * @date 2012-8-6
 */

class User_Cookie
{
    /**
     * @var array cookie写入的域名
     */
    private $domain = '';
    /**
     * @var string 密匙
     */
    private $secureKey = '';

    /**
     * @var string 内容分割符
     */
    private $sign = '|';

    /**
     * @var int 用户id
     */
    private $uid = 0;

    /**
     * @var int 登录时间
     */
    private $time = 0;

    /**
     * @var cookie过期时间
     */
    private $expire = 315360000; //10*365*86400;
    /**
     * @var
     */
    private $uidTag = 'uid';
    /**
     * @var string
     */
    private $timeTag = "time";

    /**
     * @var string token标签名
     */
    private $tokenTag = 'token';

    private $token ;
    /**
     * 初始化
     * @param $key
     * @param string $sign
     */
    public function __construct($domain, $secureKey, $sign = '|')
    {
        $this->domain = $domain;
        $this->secureKey = $secureKey;
        $this->sign = $sign;

        if (isset($_COOKIE[$this->tokenTag]))
        {
            $token = $_COOKIE[$this->tokenTag];
            $content = self::decrypt($token, $this->secureKey);

            if ($content) {
                $data = explode($this->sign, $content);
                $this->uid = $data[0];
                $this->time = $data[1];
            }
        }
    }

    /**
     * @desc COOKIE设置
     * @param string $key
     * @param string $val
     * @param int $expire_time
     * @reutrn unknown
     */
    private function setcookie($key, $val = 0, $expire_time = 0)
    {
        setcookie($key, $val, $expire_time, '/', false, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
    }

    /**
     * 写入登录信息
     * @param $uid
     */
    public function login($uid)
    {
        $this->uid = $uid;
        $this->time = time();
        $content = $this->uid . $this->sign . $this->time;
        $token = $this->token;

        $this->setcookie($this->uidTag, $this->uid, time() + $this->expire);
        $this->setcookie($this->timeTag, $this->time, time() + $this->expire);
        $this->setcookie($this->tokenTag, $token, time() + $this->expire);
    }


    /**
     * 注销登录
     */
    public function logout()
    {
        $this->setcookie($this->uidTag, null, 0 - time());
        $this->setcookie($this->timeTag, null, 0 - time());
        $this->setcookie($this->tokenTag, null, 0 - time());

        return true;
    }

    /**
     * @return 用户id
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * 令牌加密
     * @param $origin 原始数据
     * @param $key 密匙
     * @return string
     */
    public static function encrypt($origin, $key)
    {
        /* Determine the length of the input string. */
        $input_len = strlen($origin);
        /* Determine the length of the encryption key. */
        $key_len = strlen($key);
        /* Encrypt the string. */
        $result = "";
        for ($i = 0; $i < $input_len; $i++) {
            /* Extract a character from the input string and encryption key.
            Obtain the ASCII value of the characters and perform an exclusive OR operation on them. */
            $ascii_value = ord(substr($origin, $i, 1)) ^ ord(substr($key, $i % $key_len, 1));

            /* Extract the four higher bits in the character. */
            $high4bit_val = $ascii_value >> 4;

            /* Determine the ASCII value of "A". */
            $ascii_val_A = ord("A");

            /* Determine the ASCII value for "0" (zero). */
            $ascii_val_0 = ord("0");

            /* Encrypt the value of the four higher bits of the character extracted from the input string. */
            $first_val = (($high4bit_val > 9) ? ($high4bit_val + $ascii_val_A - 10) : ($high4bit_val + $ascii_val_0));

            /* Extract the four lower bits in the character. */
            $low4bit_val = $ascii_value & 0x0f;

            /* Encrypt the value of the four lower bits of the character extracted from the input string. */
            $second_val = (($low4bit_val > 9) ? ($low4bit_val + $ascii_val_A - 10) : ($low4bit_val + $ascii_val_0));

            /* Store the encrypted characters.
            kenDecrypt
            Note that the extracter character has been encrypted into two characters. */
            $result .= (sprintf("%c", $first_val) . sprintf("%c", $second_val));
        }

        /* Return the encrypted string.
        Note the length of the encrypted string is twice the length of the original string. */
        return $result;
    }

    /**
     * @desc 令牌解密
     * @param $origin
     * @param $key
     * @return string
     */
    public function decrypt($origin, $key)
    {
        /* Determine the half length of the input string. */

        $input_half_len = (int)(strlen($origin) / 2);

        /* Determine the length of the encryption key. */
        $key_len = strlen($key);

        /* Unencrypt the string. */
        $result = "";
        for ($i = 0; $i < $input_half_len; $i++) {
            /* Extract two consecutive characters from the input string and obtain their ASCII values. */
            $first_char_ascii = ord(substr($origin, $i * 2, 1));
            $second_char_ascii = ord(substr($origin, ($i * 2) + 1, 1));

            /* Obtain the ASCII value of "A". */
            $ascii_val_A = ord("A");

            /* Obtain the ASCII value of "0" (zero). */
            $ascii_val_0 = ord("0");

            $ascii_value = (($first_char_ascii >= $ascii_val_A) ? (($first_char_ascii & 0xdf) - $ascii_val_A + 10) : ($first_char_ascii - $ascii_val_0));
            $ascii_value = $ascii_value << 4;
            $ascii_value += (($second_char_ascii >= $ascii_val_A) ? (($second_char_ascii & 0xdf) - $ascii_val_A + 10) : ($second_char_ascii - $ascii_val_0));

            /* Convert the ASCII value to character and store the unencrypted character. */
            $result .= (sprintf("%c", ($ascii_value ^ ord(substr($key, $i % $key_len, 1)))));
        }

        /* Return the unencrypted string. */
        return $result;
    }

    public function settoken($token){
        $this->token = $token ;
    }

    public function gettoken(){
        return $this->token;
        $_COOKIE['token'];
    }


}


