<?php
/**
 * Created by PhpStorm.
 * User: yyswoole
 * Date: 2018/11/19
 * Time: 17:27
 */

namespace model\test;

use model\Model;

class TestModel extends Model
{


    public function Index()
    {
        return json_encode(Model::iniPdo()->getAll('show databases;'));
    }
    
    public function getRedis()
    {
        Model::iniRedis()->hSet('aa', 'ss', 121);
        return json_encode(Model::iniRedis()->hGetAll('aa'));
    }
}