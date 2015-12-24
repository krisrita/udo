<?php

/**
 * Created by PhpStorm.
 * User: open
 * Date: 2015/5/27
 * Time: 21:25
 */
class Common_DES
{

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


}
