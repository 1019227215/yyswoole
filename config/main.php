<?php
/**
 * Created by PhpStorm.
 * User: yyswoole
 * Date: 2018/11/19
 * Time: 18:34
 */

return array(

    'mysql' => [
        'db' => [
            'hosts' => 'mysql:host=127.0.0.1;dbname=mysql;port=3306',
            'username' => 'root',
            'password' => 'root',
            'options' => [
                'charset' => 'utf8',
            ]
        ]
    ],

    'redis' => [
        'db' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'auth' => ''
        ]
    ],

    'default' => [
        '127.0.0.1' => [
            'mysql' => 'db',
            'redis' => 'db',
            'domainname' => 'www.test.com',
            'port' => 80,
            'safety' => ['chroot' => S_ROOT, 'group' => 'www', 'user' => 'www',],
        ],

    ],

    'render' => [

        //静态文件目录名，位于public下
        'static_url' => 'html',

        //静态文件格式
        'static' => ['ico', 'js', 'map', 'css', 'png', 'jpg', 'git', 'otf', 'fon', 'font', 'ttc', 'eot', 'svg', 'ttf', 'woff', 'woff2'],

        //动态文件格式
        'dynamic' => ['html', 'php', 'htm'],

    ],

);