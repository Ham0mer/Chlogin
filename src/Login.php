<?php
/**
 * 登录类
 * @author : hammer <oio_qwq@proton.me>
 * Licensed ( https://lsls.me )
 * Copyright (c) 2022~2099 https://lsls.me All rights reserved.
 */
namespace ham0mer\chlogin;

class Login
{
    const QQ = "qq";  //QQ登录

    const WECHAT = "wechat";  //微信登录

    const WEIBO = "weibo";  //微博登录

    const GITHUB = "github";  //github登录

    const GITEE = "gitee";  //gitee登录

    const BAIDU = "baidu";  //百度登录

    const ALIPAY = "alipay";  //支付宝登录
    static function getApp($login = self::QQ,array $config = array())
    {
        if (strtolower($login) == self::QQ) {
            $app = QQ::init($config);
        } elseif (strtolower($login) == self::WECHAT) {
            $app = Wechat::init($config);
        } elseif (strtolower($login) == self::WEIBO) {
            $app = Weibo::init($config);
        } elseif (strtolower($login) == self::GITHUB) {
            $app = Github::init($config);
        } elseif (strtolower($login) == self::GITEE) {
            $app = Gitee::init($config);
        } elseif (strtolower($login) == self::BAIDU) {
            $app = Baidu::init($config);
        } elseif (strtolower($login) == self::ALIPAY) {
            $app = Alipay::init($config);
        } else {
            throw new \Exception("暂不支持该登录方式");
        }
        return $app;
    }
}
