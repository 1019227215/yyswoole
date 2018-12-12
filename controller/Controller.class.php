<?php
/**
 * Created by PhpStorm.
 * User: zhangyu
 * Date: 2018/11/14
 * Time: 17:37
 */

namespace controller;


use components\Tool;

class Controller
{

    public $request = [];//get/post参数
    public static $server;//更多请求信息
    public static $response;//cookie信息
    public static $swoole;//swoole对象

    public function __construct($server, $response, $swoole)
    {
        ini_set('date.timezone', 'Asia/Shanghai');
        self::$server = $server;
        self::$response = $response;
        self::$swoole = $swoole;
        self::setGetPost();
    }

    /**
     * 合并get和post数据
     * @return array
     */
    private function setGetPost()
    {

        //得到上传参数Post&Get
        if (is_array(self::$server->get) && is_array(self::$server->post)) {

            $this->request = self::$server->get + self::$server->post;
        } else {

            $this->request = is_array(self::$server->get) ? self::$server->get : $this->request;
            $this->request = is_array(self::$server->post) ? self::$server->post : $this->request;
        }

        //获取body内容(逻辑需要可以按照这个获取)
        if (!empty(self::$server->rawContent())) {

            $this->request['body'] = self::$server->rawContent();
        }

        //$this->request = Tool::handle($this->request);//删除特殊符号(下架参数过滤，让逻辑处理)

        //写日志
        if (!empty($this->request)) {

            Tool::setFile(LogDir . 'Request.log', ['url' => self::$server->server['request_uri'], 'ip' => self::$server->server['remote_addr']] + $this->request);
        }

        return $this->request;
    }

    /**
     * 加载视图文件
     * @param $files
     * @param $data
     * @return string
     */
    public static function renderView($files, $data, $caches = true)
    {
        $data = Tool::render(View . $files, $data);

        if ($caches) {

            self::staticHtml($files, $data);
        }
        return $data;
    }

    /**
     * 静态化html
     * @param $fname
     * @param $fconten
     * @return bool
     */
    public static function staticHtml($fname, $fconten)
    {
        $fname = explode('.', $fname);
        $fname = Puc . main['render']['static_url'] . '/' . $fname[0] . '.html';

        return Tool::setFile($fname, $fconten, 0, false, false, false);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

}