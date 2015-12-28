<?php
/**
 * 配置
 * @author yuanxch <449127727@qq.com>
 * @date 2013/6/7
 */
final class Common_Config
{
    const SITE_DOMAIN = "http://127.0.0.1:9097";
    const BASE_URL = "http://127.0.0.1:9097";
    /*const SITE_DOMAIN = "http://123.57.224.70:8080";
    const BASE_URL = "http://123.57.224.70:8080";*/
    
    //const BASE_URL_7NIU = "http://7sbr2j.com2.z0.glb.qiniucdn.com/";
    const BASE_7NIU = "http://7sbqhl.com2.z0.glb.qiniucdn.com/";
    //笔记
    const NOTE_BASE_URL = "http://7sbr2j.com2.z0.glb.qiniucdn.com/";
    //课程
    const BASE_URL_7NIU = "http://7sbqhl.com2.z0.glb.qiniucdn.com/w";
    //头像
    const AVATOR_BASE_URL = "http://7sbqhl.com2.z0.glb.qiniucdn.com/avatar/";
    //视频
    const VIDEOIMG_BASE_URL = "http://7sbr2j.com2.z0.glb.qiniucdn.com/";
    //学校图片
    const WEB_SCHOOL_BASE_URL = "http://7sbqhl.com2.z0.glb.qiniucdn.com/school/w%s";
    const APP_SCHOOL_BASE_URL = "http://7sbqhl.com2.z0.glb.qiniucdn.com/school/app/%s";
    //试卷图片    
    //const PAPER_BASE_URL = "http://7sbqhl.com2.z0.glb.qiniucdn.com/%s";
    

    const STATIC_BASE_URL = "http://127.0.0.1:9097/";
    /*const STATIC_BASE_URL = "http://123.57.224.70:8080/";*/
    const VIDEO_BASE_URL = "http://182.92.115.116/Api/Video/PlayVideo?video_id=%d&uid=%d";

    const API_BASE_URL = "http://182.92.115.116/Api/";
    const API_USER_REG_CODE = "http://182.92.115.116/Api/Account/GetCode";
    const API_USER_REG = "http://182.92.115.116/Api/Account/Register";

    const SECURE_KEY = "abc123";
    const SECURE_SIGN = "|";
    const DEFAULT_AVATOR = '2ffe930c8482bf639efb8d250e691806.png';

    public static $practiseChoice = array("单选题", "多选题", "选择题");

    static $elastic_config = array(
        //'hosts' => array('182.92.115.116:6800', '182.92.115.116:7000'),
        'hosts' => array('182.92.115.116:6800', '182.92.115.116:6800'),
        'retries' => 0    
        );


    static $sms = array(
		//主帐号
		"accountSid"=> 'aaf98f894a188342014a3c4467a9137f',

		//主帐号Token
		"accountToken"=> '7312a90bcb6040b5afd89a649fc656a0',
		
		//应用Id
		"appId"=>'8a48b5514a27bb6c014a3c8561c10cc5',
		
		//请求地址，格式如下，不需要写https://
		"serverIP"=>'app.cloopen.com',
		
		//请求端口
		"serverPort"=>'8883',
		
		//REST版本号
		"softVersion"=>'2013-12-26'    
    );

    //七牛
    const QINIU_ACCESS_KEY = "59jduPphUtEg2MX5csuao09VU3T2l8cjBfM4mFdr";
    const QINIU_SECRET_KEY = "iB-_d3fzBiITQrMj5hPedHt5lJwUvVzJYBrNIib6";
    const QINIU_BUCKET_IMAGES = "xdf-image";
    
    const QINIU_BUCKET_AVATOR = "xdf-image/avatar";
    const QINIU_BUCKET_COURSE = "xdf-image/course";
    const QINIU_BUCKET_NOTE = "xdf-video";

    const IMAGE_SAVE_PATH = "/tmp/";
    static $allowImageExt = array("jpg", "jpeg", "png", "gif");
    const ALLOW_IMAGE_SIZE = 2097152;

    //app下载
    const APP_ANDROID_DOWNLOAD_URL = "http://127.0.0.1/download/anroid.apk";
    const APP_APPSTORE_DOWNLOAD_URL = "https://itunes.apple.com/cn/app/xxxxx/idxxxx";

    //SSO
    //SSO用户登录接口
    const SSO_LOGIN_URL = "http://123.56.131.22/sso/oapi/login";
    const SSO_LOGIN_URL_FORMAL = "http://sso.howdo.cc/sso/oapi/login";
    //SSO用户验证接口
    const SSO_VERIFY_URL = "http://123.56.131.22/sso/oapi/verify_token";
    //SSO获取某用户可以访问的频道列表接口
    const SSO_SCHOOL_URL = "http://123.56.131.22/sso/oapi/user_entry";
    const SSO_SCHOOL_URL_FORMAL = "http://sso.howdo.cc/sso/oapi/user_entry";
    //SSO获取所有频道接口
    const SSO_SCHOOL_UPDATE_URL = "http://123.56.131.22/sso/oapi/user_entry";
    //SSO获取所有用户接口
    const SSO_USER_URL = "http://123.56.131.22/sso/oapi/user_entry";
    //SSO通知推送接口
    const SSO_MESSAGE_URL = "http://123.56.131.22:8080/pm/api/push/add";
    const SSO_TRANS_URL = "http://123.56.131.22:8080/pm/api/push/trans";
    //SSO查询频道接口S
    const SSO_ENTRY_URL = "http://123.56.131.22//sso/oapi/entry";
    //SSO查询频道的用户权限接口
    const SSO_USER_ENTRY_URL = "http://123.56.131.22//sso/oapi/entry/usersid";
    //SSO根据用户id获取用户信息接口
    const SSO_USER_INFO = "http://123.56.131.22/sso/oapi/user/byid";
    //SSO获取用户所有可用资源接口
    const SSO_USER_RESOURCE = "http://123.56.131.22/ua/oapi/res/tree";
    //SSO获取用户单点可用资源接口
    const SSO_USER_SINGLE_RESOURCE = "http://123.56.131.22/ua/oapi/res/single";
    //云平台提供的支付接口
    const UDO_PAY_SERVICE = "http://123.56.131.22/pay/oapi/trans/prepay";
    const TRANS_QUERY = "http://123.56.131.22/pay/oapi/trans/query";
    const PAY_OSID = '3388';
    const PAY_SECRET = 'xpmJIWtPsBejN1r5aPryPx3QFypV2CuP';

    //运营中心接收
    const PAY_NOTIFY_URL = "http://182.92.118.115:8080/Account/getPayResult";
    const UDO_OP_DOMAINID = 2706;
    const UDO_APP_DOMAINID = 2703;
    const UDO_OP_SIGNTYPE = 0;
    const UDO_OP_SECRET_APP = "5SluG07eUnTAJAH4LN3xUfCxxDbN4d6N";
    const UDO_OP_SECRET_SERVER = "HETZyXBltlH4iKlWzOcOnAryhmxJ0PXY";
    const UDO_AES_KEY = "user_pass_enckey";

    //消息类型
    const UDO_SCHOOL_MESSAGE_TYPE = 0;
    const UDO_FRONT_ITEM_MESSAGE = 0;

    /*
     * 学分行为
     */
    const CREDIT_VIDEO_UP = 101;
    const CREDIT_VIDEO_DOWN = 102;
    const CREDIT_COMMENT = 103;
    const CREDIR_ALL_ANSWER = 106;
    const CREDIT_SINGLE_ANSWER = 107;
    const CREDIT_MY_MISSION = 110;
    const CREDIT_SHARE = 200;
    /*
     * 学分的获取/消耗来源
     * 积分来源：0-日常操作；1-任务；2-代币兑换；3-购买消耗
     */
    const DAILY_CREDIT_ACTION = 0;
    const CREDIT_MISSION = 1;
    const CREDIT_COIN_EXCHANGE = 2;
    const CREDIT_BUY = 3;

    /*
     * 学分日志中的状态
     */
    const CREDIT_SUCCESS = 1;
    /*本地资源注册到云平台后的localType
    *11课程
    12子课程
    21章
    22节
     */
    const UDO_LOCAL_COURSE_TYPE = 11;
    const UDO_LOCAL_CHAPTER_TYPE = 21;
    const UDO_LOCAl_SECTION_TYPE = 22;
    const PUBLIC_COURSE_TYPE = 6;
    const PUBLIC_CHAPTER_TYPE = 7;
    const PUBLIC_SECTION_TYPE = 8;


    /*
     * 支付相关的常数
     */
    /*
     * 运营交互中的支付类型
     * 支付对象的类型，1-U币支付;2-U币充值；3-学分支付
     */
    const UDO_PAYTYPE_COIN  = 1;
    const UDO_PAYTYPE_RECHARGE = 2;
    const UDO_PAYTYPE_CREDIT = 3;

    /*
     * 和账户系统交互时的参数类型
     * 1- 余额支付；2-渠道支付
     */
    const PUBLIC_PAYTYPE_AMOUNT = 1;
    const PUBLIC_PAYTYPE_CHANNEL = 2;

    /*
     * 购买的资源类型
     */
    const UDO_RESOURCE_SCHOOL = 1;
    const UDO_RESOURCE_COURSE = 2;
    /*
     * 定价类型
     */
    const UDO_PRICETYPE_CREDIT = 1;
    const UDO_PRICETYPE_COIN = 2;
    const UDO_PRICETYPE_FREE = 3;

    /*
     *
     */


    /*
     * 订单状态相关的常数
     * 订单状态,0-支付成功;1-支付状态未确认;2-支付失败
     */
    const ORDER_SUCCESS = 0;
    const ORDER_FAIL = 2;
    const ORDER_NOT_PAY = 1;
    const ORDER_CLOSED = 3;

    /*
     * 支付通知的sign校验结果
     */
    const NOTIFY_SOLID = 1;
    const NOTIFY_NOT_SOLID = 2;
    const NOTIFY_UNCERTAIN = 4;

    /*
     * 交易中需要获取的日志类型
     * 1- 学分日志;2-U币日志
     */
    const CREDIT_LOG = 1;
    const COIN_LOG = 2;

    /*
     * 支付中的行为记录：
     *  1- 记录进入订单的信息；
        2- 记录订单上的全选/反选操作
        3- 记录用户取消支付/立即支付操作
        4- 记录用户从已购课程列表进入课程
     */
    const LOG_ENTER_ORDER = 1;
    const LOG_SELECT_ALL = 2;
    const LOG_SELECT_PAY = 3;
    const LOG_ENTER_BOUGHT = 4;

    /*
     * 支付行为记录中的位置
     * 1-频道首页（未经筛选）；2-频道首页（经过筛选）；3-课程页面；4-已购课程列表
     */
    const POS_SCHOOL_PAGE = 1;
    const POS_SCHOOL_FILTER = 2;
    const POS_COURSE_PAGE = 3;
    const POS_BOUGHT_COURSE = 4;

    /*
     * 支付行为：
     *  1-取消全选；
        3-取消支付；
        4-确认支付
     */
    const ACTION_CANCEL_SELECT = 1;
    const ACTION_CANCEL_PAY = 3;
    const ACTION_CONFIRM_PAY = 4;

    /**
     * 不需要登录
     */
    public static $NotNeedLogin = array(
        "User" => array(
            "register" => 1, 
            "login" => 1, 
            "forgetpassword" => 1, 
            "isuserexists" => 1, 
            "checkcode" => 1, 
            "sendcode" => 1, 
            "forgetpasswordsendcode" => 1
        ),

        "Course" => array("profile" => 1),
        "Practise" => array("subject"=>1, "option" => 1, 'wrong'=> 'ios', 'parse' => 'ios','exam'=>1),
        "Paper" => array("list" => 1),
    );

    public static function getVideoUrl($id)
    {
        return sprintf(self::VIDEO_BASE_URL, $id, (int)Yaf_Registry::get("uid"));
    }


    /**
     * 获得课程图片的base url
     */
    public static function getCourseImageUrl($key)
    {
        $platform = Yaf_Registry::get("platform");

        switch ($platform) {
            case "ios":
            case "android":
                return sprintf(self::BASE_7NIU . "app/%s", $key);
            case "web":
            default:
                return sprintf(self::BASE_7NIU . "w%s", $key);
     
        }
    }

    public static function getVideoImageUrl($key)
    {
        return self::VIDEOIMG_BASE_URL . sprintf("%s/resource/note/1.png", $key);
    }

    public static function getShoolImageUrl($key, $flag = 0)
    {
        $platform = Yaf_Registry::get("platform");

        switch ($platform) {
            case "ios":
            case "android":
                return sprintf(self::APP_SCHOOL_BASE_URL, $key);
            case "web":
            default:
                return sprintf(self::WEB_SCHOOL_BASE_URL, $key);
     
        }
    }

    /**
     * 图片格式
     */
    public static function getPaperImageUrl($key)
    {
        return sprintf(self::PAPER_BASE_URL, $key);
    }



}
