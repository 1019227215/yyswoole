<?php
/**
 * 入口文件
 * Created by PhpStorm.
 * User: yyswoole
 * Date: 2018/11/12
 * Time: 11:11
 */

define('S_ROOT', __DIR__);
define('Cl', 'controller');
define('Di', 'action');
define('LogDir', '/log/');
define('UpDir', '/UpDir/');
define('Puc', '/Public/');
define('View', '/view/');
ini_set('memory_limit', '-1');
declare (ticks = 1);

try {

    include_once S_ROOT . "/controller/YYS.class.php";

    \controller\YYS::loaders();//自动加载类
    \components\Tool::getDefault();//加载main为常量

    if (!defined('DomainName') || !defined('Port')) {

        echo 'Please configure your default domain name and port';
        die;
    }

    $chroot = main['default'][Ips]['safety']['chroot'] ?? '/';
    $user = main['default'][Ips]['safety']['user'] ?? 'root';
    $group = main['default'][Ips]['safety']['group'] ?? 'root';

    $http = new swoole_http_server(DomainName, Port);

    $http->on("start", function ($server) {

        echo "Swoole http server is started at http://" . DomainName . ":" . Port . PHP_EOL;
    });

    $http->set(array(

        'upload_tmp_dir' => S_ROOT . UpDir,//上传文件存储目录
        'document_root' => S_ROOT . Puc . main['render']['static_url'] . '/',//静态html文件目录
        'enable_static_handler' => true,//开启静态优先访问
        'http_compression' => true,//开启压缩输出
        'chroot' => $chroot,
        'user' => $user,
        'group' => $group,

    ));

    $http->on("request", function ($request, $response) use ($http, $user) {

        if (isset($request->get['reload']) && $request->get['reload'] == "yes") {

            if ($user != 'root') {
                swoole_async::exec("kill -USR2 {$http->worker_pid}", function ($result, $status) {
                    exit;
                });
            }else{
                $http->reload(true);
            }
        }

        $data = \controller\YYS::run($request, $response, $http);
        $data = is_string($data) ? $data : json_encode($data);
        $response->end($data);

    });

    $http->start();

} catch (Exception $e) {

    echo $e->getMessage() . PHP_EOL;

    exit;
}

