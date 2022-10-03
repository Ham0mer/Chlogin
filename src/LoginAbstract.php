<?php
/**
 * 定义登录接口的公共方法
 * @author : hammer <oio_qwq@proton.me>
 * Licensed ( https://lsls.me )
 * Copyright (c) 2022~2099 https://lsls.me All rights reserved.
 */
namespace ham0mer\chlogin;
abstract class LoginAbstract
{
    abstract function login();

    abstract function callback($state,$code);
}
