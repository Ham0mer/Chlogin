# Chlogin
## PHP彩虹聚合登录第三方登录类库

### 安装

~~~bash
composer require ham0mer/chlogin
~~~


#### 类库列表，持续更新
~~~
支持QQ,Alipay,baidu,gitee,github,wx,sina,huawei,xiaomi,google,microsoft,facebook,twitter,dingtalk登录
~~~


### 使用方法

~~~php
//登录方法
$config = array()
$login = Login::getApp($config);
$login->login();

//登录回调
$config = array()
$login = Login::getApp($config);
$login->callback($state,$_GET['code']);
~~~

### 配置强调
~~~php
$config['framework'] = 'tp';//framework为空使用原生$_SESSION, tp使用thinkphp的session助手函数
~~~

### 登录示例：
~~~php
$config = [
      'url' => 'https://login.fan/' . 'connect.php',
      // 聚合平台获取
      'app_id' => '10000',
      // 聚合平台获取
      'app_key' => 'xxx',
      // 回调地址
      'callback' => '/api/index/qqcallback',
      'framework' => 'tp',
      'type'      =>  'qq'//此处为登录类型，支持QQ,Alipay,baidu,gitee,github,wx,sina,huawei,xiaomi,google,microsoft,facebook,twitter,dingtalk登录
  ];

/**
 * QQ登录
 */
 use \Ham0mer\Chlogin\Login;
function qqLoginAction()
{
    // qq登录
    $login = Login::getApp($config);
    $login->login();
}

/**
 * QQ登录回调
 */
function qqCallbackAction()
{
    $state = session('qquser.state');
    $login = Login::getApp($config);
    // 获取用户信息
    $userinfo = $login->callback($state,$_GET['code']);
    return userinfo;
}
~~~
