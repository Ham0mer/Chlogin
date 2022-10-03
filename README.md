# Chlogin
## PHP彩虹聚合登录第三方登录类库

### 安装

~~~
composer require ham0mer/chlogin
~~~


#### 类库列表，持续更新
~~~
QQ登录

微信登录

微博登录
~~~


### 使用方法

~~~
//登录方法
$name = 'qq',$config = array()
$login = \login\Login::getApp($name,$config);
$login->login();

//登录回调
$name = 'qq',$config = array()
$login = \login\Login::getApp($name,$config);
$login->getUserInfo();
~~~

### 配置强调
~~~
$config['framework'] = 'tp';//framework为空使用原生$_SESSION, tp使用thinkphp的session助手函数
~~~

### QQ登录示例：
~~~
$name = 'qq';
$config = array(
    //开发平台获取
    'app_id' => '101389004',
    //开发平台获取
    'app_key' => '5023acb17c76531a664e995b89e5de07',
    //回掉地址，需要在腾讯开发平台填写
    'callback' => "/index/user/qqcallback",
    'scope' => 'get_user_info',
    'expires_in' => 7775000
);

/**
 * QQ登录
 */
function qqLoginAction()
{
    // qq登录
    $this->_set_referer();
    $login = \login\Login::getApp($name,$config);
    $login->login();
}

/**
 * QQ登录回调
 */
function qqCallbackAction()
{
    $login = \login\Login::getApp($name,$config);
    // 获取用户信息
    $userinfo = $login->getUserInfo();

    if (! isset($userinfo['openid']) || empty($userinfo['openid'])) {
        return $this->redirect(url("index/index/index"));
    }
    // 查询是否存在
    $user = User::get(array(
        'qq_openid' => $userinfo['openid']
    ));
    if ($user) {
        // 账号存在去登录
        return $this->_toLogin($user, false);
    } else {
        // 新注册该用户
        Session::set("qq_userinfo", $userinfo);
        return $this->redirect(url("index/user/newAccount"));
    }
}
~~~

### 微信登录示例：
~~~
$name = 'weixin';
$config = array(
    //开发平台获取
    'app_id' => 'wx587351c59b2fbca4',
    //开发平台获取
    'app_secret' => '382b75b03fa71c5691555c65037598dc',
    //回掉地址，需要在腾讯开发平台填写
    'callback' => "/default/user/wxcallback",
    //终端类型
    'terminal' => "pc",//pc为电脑端扫码登录，否则微信公众号登录
    //手机端回调地址
    'callback_wx' => "/wap/user/wxcallback",
    //订阅号appid
    'app_id_d' => 'wxae475941e485a3a8',
    //订阅号app_secret
    'app_secret_d' => '3ca2f30daa500012a51b0d126e83eefe'
);

//登录
$login = \login\Login::getApp($name,$config);
$login->login();

//回调获取信息
$login = \login\Login::getApp($name,$config);
$userinfo = $login->getUserInfo();
~~~
