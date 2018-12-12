<?php
/**
 * Created by PhpStorm.
 * User: zhangyu
 * Date: 2018/11/14
 * Time: 17:36
 */

namespace controller;

use controller\Controller;

class IndexController extends Controller
{

    public function Checkpart()
    {

        return json_encode($this->request);
    }

    public function actionIndex()
    {

        return self::renderView("index.php", ['test' => '这是第一次更新！']);
        //return json_encode($_SERVER);
    }

}