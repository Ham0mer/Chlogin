<?php
/**
 * 登录类
 * @author : hammer <oio_qwq@proton.me>
 * Licensed ( https://lsls.me )
 * Copyright (c) 2022~2099 https://lsls.me All rights reserved.
 */
namespace Ham0mer\Chlogin;

class Login
{
//    const QQ = "qq";  //QQ登录
//
//    const WEIBO = "weibo";  //微博登录
//
//    const GITHUB = "github";  //github登录
//
//    const GITEE = "gitee";  //gitee登录
//
//    const BAIDU = "baidu";  //百度登录
//
//    const ALIPAY = "alipay";  //支付宝登录
    static function getApp(array $config = array())
    {
        $app = QQ::init($config);

        return $app;
    }
}