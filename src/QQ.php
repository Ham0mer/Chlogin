<?php
/**
 * QQ登录类
 * @author : hammer <oio_qwq@proton.me>
 * Licensed ( https://lsls.me )
 * Copyright (c) 2022~2099 https://lsls.me All rights reserved.
 */
namespace Ham0mer\Chlogin;

class QQ extends LoginAbstract
{
    private $_config = array(
        'url'   => 'https://login.fan/'.'connect.php',
        // 聚合平台获取
        'app_id' => '',
        // 聚合平台获取
        'app_key' => '5023acb1767d931a664e995b89e5de07',
        // 回调地址
        'callback' => '/index/user/qqcallback',
        'framework' => 'tp'
        );
    // 全局唯一实例
    private static $_app = null;

    private function __construct($config)
    {
        $this->_config = array_replace_recursive($this->_config, $config);
    }

    static function init($config)
    {
        if (null == self::$_app) {
            self::$_app = new QQ($config);
        }

        return self::$_app;
    }
    /**
     * 登录方法，交换code
     * 可传递回调方法url，不传则实用config里面的回调地址
     */
    function login($callback = null){
        $appid = $this->_config['app_id'];
        if ($callback == null) {
            $callback = $this->getCallback($this->_config['callback']);
        } else {
            $callback = $this->getCallback($callback);
        }
        // -------生成唯一随机串防CSRF攻击
        $state = md5(uniqid(rand(), TRUE));
        if($this->_config['framework']=='tp'){
            session('qquser.state', $state);
        }else{
            $_SESSION['qquser.state'] = $state;
        }

        //-------构造请求参数列表
        $keysArr = array(
            "act" => "login",
            "appid" => $appid,
            "appkey" => $this->_config['app_key'],
            "type" => 'qq',
            "redirect_uri" => $this->_config['callback'],
            "state" => $state
        );
        $login_get = $this->get($this->_config['url'], $keysArr);
        $login_date = json_decode($login_get,true);
        $login_url = $login_date['url'];
        header("Location:$login_url");
        exit();
    }
    //登录成功返回网站
    public function callback(){
        // --------验证state防止CSRF攻击
        if($this->_config['framework']=='tp'){
            $state = session('qquser.state');
        }else{
            $state = $_SESSION['qquser.state'];
        }

        if ($_GET['state'] != $state) {
            return $this->showError('0', "验证过期，请重新操作");
            exit();
        }
        //-------请求参数列表
        $keysArr = array(
            "act" => "callback",
            "appid" => $this->_config['app_id'],
            "appkey" => $this->_config['app_key'],
            "code" => $_GET['code']
        );

        //------构造请求access_token的url
        $response = $this->get($this->_config['url'], $keysArr);

        return $response;
    }
    //查询用户信息
    public function getUserInfo($social_uid){
        //-------请求参数列表
        $keysArr = array(
            "act" => "query",
            "appid" => $this->_config['app_id'],
            "appkey" => $this->_config['app_key'],
            "type" => 'qq',
            "social_uid" => $social_uid
        );

        //------构造请求access_token的url
        $response = $this->get($this->_config['url'], $keysArr);

        $response = json_decode($response,true);
        return $response;
    }

    /**
     * 获取回调地址
     *
     * @param unknown_type $callback
     * @return multitype:string number
     */
    function getCallback($callback)
    {
        if (strpos($callback, 'http://') === false && strpos($callback, 'https://') === false) {
            $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' :'http://';
            $this->_config['callback'] = $http_type . $_SERVER['HTTP_HOST'] . $callback;
        }else {
            $this->_config['callback'] = $callback;
        }

        return $this->_config['callback'];
    }
    /**
     * combineURL
     * 拼接url
     *
     * @param string $baseURL
     *            基于的url
     * @param array $keysArr
     *            参数列表数组
     * @return string 返回拼接的url
     */
    public function combineURL($baseURL, $keysArr)
    {
        $combined = $baseURL . "?";
        $valueArr = array();

        foreach ($keysArr as $key => $val) {
            $valueArr[] = "$key=$val";
        }

        $keyStr = implode("&", $valueArr);
        $combined .= ($keyStr);

        return $combined;
    }

    /**
     * get_contents
     * 服务器通过get请求获得内容
     *
     * @param string $url
     *            请求的url,拼接后的
     * @return string 请求返回的内容
     */
    public function get_contents($url)
    {
        if (ini_get("allow_url_fopen") == "1") {
            $response = file_get_contents($url);
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        // -------请求为空
        if (empty($response)) {
            return $this->showError("50001", "<h2>可能是服务器无法请求https协议</h2>可能未开启curl支持,请尝试开启curl支持，重启web服务器，如果问题仍未解决，请联系我们");
        }

        return $response;
    }

    /**
     * get
     * get方式请求资源
     *
     * @param string $url
     *            基于的baseUrl
     * @param array $keysArr
     *            参数列表数组
     * @return string 返回的资源内容
     */
    public function get($url, $keysArr)
    {
        $combined = $this->combineURL($url, $keysArr);
        return $this->get_contents($combined);
    }

    /**
     * post
     * post方式请求资源
     *
     * @param string $url
     *            基于的baseUrl
     * @param array $keysArr
     *            请求的参数列表
     * @param int $flag
     *            标志位
     * @return string 返回的资源内容
     */
    public function post($url, $keysArr, $flag = 0)
    {
        $ch = curl_init();
        if (! $flag)
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $keysArr);
        curl_setopt($ch, CURLOPT_URL, $url);
        $ret = curl_exec($ch);

        curl_close($ch);
        return $ret;
    }

    /**
     * showError
     * 显示错误信息
     *
     * @param int $code
     *            错误代码
     * @param string $description
     *            描述信息（可选）
     */
    public function showError($code, $description = '$')
    {
        echo "<meta charset=\"UTF-8\">";
        echo "<h3>error:</h3>$code";
        echo "<h3>msg  :</h3>$description";
        exit();
    }
}