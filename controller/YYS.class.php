<?php
/**
 * Created by PhpStorm.
 * User: yyswoole
 * Date: 2018/11/16
 * Time: 10:55
 */

namespace controller;


class YYS
{

    /**
     * 初始化控制器
     * 根据路径斜杠拆分取得路径
     */
    public static function run($server, $response, $swoole)
    {
        \components\Tool::setFile(LogDir . "Run.log", json_encode($server));//记录每次请求日志

        $url = trim($server->server['request_uri'], '/');//去掉前后斜杠
        $url = explode('?', $url);//以问号拆分找地址
        $suffix = explode('.', $url[0]);//以点拆分找后缀
        $fixs = end($suffix);

        //静态文件检查输出
        if (isset($fixs) && in_array($fixs, main['render']['static'])) {

            if (file_exists(Puc . $url[0])) {

                return \components\Tool::render(Puc . $url[0]);
            }
        }

        //动态文件检查输出
        if (isset($fixs) && in_array($fixs, main['render']['dynamic'])) {

            return self::dynamic($suffix, $server, $response, $swoole);
        } else {

            return 'Error! Suffix not found!';
        }
    }

    /**
     * 渲染动态文件
     * @param $suffix
     * @param $server
     * @param $response
     * @param $swoole
     * @return string
     */
    private static function dynamic($suffix, $server, $response, $swoole)
    {
        $route = \components\Tool::handle(explode('/', $suffix[0]));//以斜杠拆分找路径,同时去除特殊符号
        $route = array_filter($route);//去除空值

        if (empty($route)) {

            return 'Error! The interpreter does not exist!';
        }

        $ide = (count($route) > 1) ? array_pop($route) : end($route);//得到最后一个元素
        $index = Di . ucfirst($ide);//得到方法名
        $filename = array_pop($route);//得到最后一个元素
        $url = empty($route) ? '' : implode('\\', $route) . '\\';//拼装地址
        $controller = ucfirst($filename) . ucfirst(Cl);//控制器名称
        $conter = '\\' . Cl . '\\' . $url . $controller;//拼装文件地址
        $file = str_replace('\\', '/', $conter) . '.class.php';//控制地址

        if (file_exists($file)) {

            $ado = new $conter($server, $response, $swoole);//初始化

            return method_exists($ado, $index) ? $ado->$index() : "Error! {$ide} not found!";//执行方法
        } else {

            return "Error! {$controller} not found!";
        }

    }

    /**
     * 自动加载类
     */
    public static function loaders()
    {
        spl_autoload_register(function ($className) {

            $file = str_replace('\\', '/', $className) . '.class.php';

            //var_dump($file,S_ROOT,$className);
            if (file_exists($file)) {

                include_once $file;
            } else {

                return "Error! {$className} Content cannot be processed!";
            }
        });
    }
}