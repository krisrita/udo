<?php

final class Common_Error
{

	const ERROR_SUCCESS = 1000;
    const ERROR_FAIL = 0;
	
	const ERROR_SYSTEM = - 1000;
	const ERROR_REQ = - 1001; 

    const ERROR_TOKEN_NOT_EXISTS = -1003;
    const ERROR_TOKEN = -1004;

	const ERROR_USER_NOT_LOGIN = - 1101;
    const ERROR_USER_EXISTS = - 1102;
	const ERROR_USER_NOT_EXISTS = - 1103;
	const ERROR_USER_LOGIN_FAIL = -1104;
    const ERROR_USER_PASSWORD = -1105;    
    const ERROR_USER_FROZEN = -1106;
    const ERROR_USER_PERMISSION = - 1107;
    const ERROR_USER_REG_CODE_NOT_EXISTS = -1108;
    const ERROR_USER_REG_CODE = -1109;
    const ERROR_USER_REG = -1110;
    const ERROR_USER_SEND_CODE = -1111;
    const ERROR_USER_PASSWORD_WRONG = -1112;
    const ERROR_USER_REG_MOBILE = -1113;
    const ERROR_USER_PASSWORD_FORMAT = -1114;

    
	const ERROR_MYSQL_CONNECT = - 1201; 
	const ERROR_MYSQL_EXECUTE = - 1202;



    const ERROR_COURSE_NOT_EXISTS = 1301;
    const ERROR_VIDEO_NOT_EXISTS = 1302;


    
    const ERROR_UPLOAD = -1401;
    const ERROR_UPLOAD_NOT_EXISTS = -1402;

    const ERROR_PRACTISE_HAS_BEEN_DONE = -1501;
    const ERROR_PRACTISE_HAS_NOT_BEEN_DONE = -1502;
    const ERROR_PRACTISE_NO_WRONG_PARSE = -1503;
    const ERROR_PRACTISE_NOT_EXISTS = -1504;



    
	
	const ERROR_VERIFY_SEND = -2000;

    //频道返回时的错误提示
    const ERROR_SSO_ERROR_SCHOOL = 30001;
    const ERROR_SCHOOL_NOT_EXISTS = 30002;
    const ERROR_SSO_ERROR_USER = 30003;
    const ERROR_FIRST_UPDATE = 30004;
    const ERROR_NO_SEARCH_RESULT = 30005;
    const INVALID_TOKEN = -30006;

    const ERROR_PARAM = 30011;
    //消息返回时的错误提示
    const ERROR_MESSAGE_NOT_EXISTS = 30101;

    //积分系统出错

    //支付时的错误提示
    const ERROR_CONNECTION_ERROR = 30201;
    const ERROR_WRITE_ERROR = 30202;
    const ERROR_ACCOUNT_ERROR = 30203;

    //生成订单时提示的错误信息
    const ERROR_SCHOOL_PRICETYPE = -30301;
    const ERROR_SCHOOL_PRICE = -30302;
    const ERROR_COURSE_PRICETYPE = -30303;
    const ERROR_COURSE_PRICE = -30304;
    const ERROR_COIN_INFO = -30305;
    const ERROR_ORDER_FAIL = -30306;
    const ERROR_ORDER_SUCCESS = 30307;
    const ERROR_COURSE_BOUGHT = -30308;
    const ERROR_SHORT_BALANCE = -30309;
    const ERROR_FAIL_BOUGHT =  -30310;
    const ERROR_BOUGHT_SUCCESS = 30311;
    const ERROR_TRANS_UNKNOWN = -30352;
    const ERROR_TRANS_FAIL = -30351;
    const ERROR_TRANS_CLOSED = -30353;

    //U币兑换学分时的出错状态
    const ERROR_UPDATE_BALANCE = -30361;
    //获取可购课程列表时的信息
    const ERROR_NO_AVAILABLE = -30310;

    //学分操作的出错状态
    const ERROR_OVER_TIMES = -30371;
    const ERROR_ACTION = -30372;
	public static $errmsg = array 
	(
		self::ERROR_SUCCESS => '成功',
        self::ERROR_FAIL => '失败',
		self::ERROR_REQ => '请求非法',
		self::ERROR_PARAM => '参数不全',
        self::ERROR_TOKEN_NOT_EXISTS => 'TOKEN不存在',
        self::ERROR_TOKEN => 'TOKEN错误',
		
		self::ERROR_USER_NOT_LOGIN => '没有登录',
        self::ERROR_USER_EXISTS => '帐号已存在',
		self::ERROR_USER_NOT_EXISTS => '用户不存在',
		self::ERROR_USER_LOGIN_FAIL => '登录失败',
        self::ERROR_USER_PASSWORD => '用户名或密码错误',
		self::ERROR_USER_FROZEN => '账户被冻结',
		self::ERROR_USER_PERMISSION => '没有权限',
        self::ERROR_USER_REG_CODE_NOT_EXISTS => '注册验证码不存在',            
        self::ERROR_USER_REG_CODE => '验证码错误',
        self::ERROR_USER_REG => '注册失败',
        self::ERROR_USER_SEND_CODE => '验证码发送失败',
        self::ERROR_USER_PASSWORD_WRONG => '当前密码错误',
        self::ERROR_USER_REG_MOBILE => '手机号码错误',
        self::ERROR_USER_PASSWORD_FORMAT => '密码为6-20位的字母，数字或下划线组成，区分大小写',

		self::ERROR_MYSQL_CONNECT => '数据库连接失败',
		self::ERROR_MYSQL_EXECUTE => '数据库执行失败',

        self::ERROR_UPLOAD => '上传失败',
        self::ERROR_UPLOAD_NOT_EXISTS => '没有文件被上传',


        self::ERROR_PRACTISE_NOT_EXISTS => "习题不存在",
        self::ERROR_PRACTISE_HAS_BEEN_DONE => "已做过该练习题",
        self::ERROR_PRACTISE_HAS_NOT_BEEN_DONE => "没有做过该练习题",
        self::ERROR_PRACTISE_NO_WRONG_PARSE => "没有错题解析",

        self::ERROR_COURSE_NOT_EXISTS => "课程不存在",

        self::ERROR_SCHOOL_NOT_EXISTS => "学校不存在",
        self::ERROR_FIRST_UPDATE => "第一次更新，不推送消息",

		self::ERROR_SYSTEM => '系统错误',

        self::INVALID_TOKEN => 'ssotoken无效',
        self::ERROR_SSO_ERROR_SCHOOL => 'SSO获取频道出错',
        self::ERROR_SSO_ERROR_USER => 'SSO获取用户出错',

        self::ERROR_MESSAGE_NOT_EXISTS => '没有新消息',

        self::ERROR_CONNECTION_ERROR => '网络请求出错',
        self::ERROR_ACCOUNT_ERROR => '账户信息出错',
        self::ERROR_WRITE_ERROR => '账户更新出错',

        self::ERROR_SCHOOL_PRICETYPE=>"频道定价类型和系统不一致",
        self::ERROR_SCHOOL_PRICE=>'频道定价金额和系统不一致',
        self::ERROR_COURSE_PRICETYPE=>'课程定价类型和系统不一致',
        self::ERROR_COURSE_PRICE=>'课程定价金额和系统不一致',
        self::ERROR_COIN_INFO => 'U币充值额度和系统不一致',
        self::ERROR_ORDER_FAIL=>'生成新订单失败',
        self::ERROR_ORDER_SUCCESS=>'成功生成新订单',
        self::ERROR_COURSE_BOUGHT=>'当前课程已经购买，请误再次生成订单',
        self::ERROR_SHORT_BALANCE=>'订单总额超越了账户余额',
        self::ERROR_TRANS_FAIL=>'交易失败',
        self::ERROR_TRANS_UNKNOWN=>'交易状态未知',
        self::ERROR_TRANS_CLOSED=>'交易已经关闭',
        self::ERROR_UPDATE_BALANCE=>'更新账户信息失败',

        self::ERROR_OVER_TIMES=>'今日该项学分增长已经超出限额',
        self::ERROR_ACTION=>'无效的动作',

	);
}