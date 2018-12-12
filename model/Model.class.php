<?php
/**
 * Created by PhpStorm.
 * User: yyswoole
 * Date: 2018/11/19
 * Time: 17:26
 */

namespace model;

use components\Pdodb;
use components\Redisdb;

class Model
{

    public function __construct()
    {

    }

    /**
     * 初始化mysqlpdo
     * @param string $type
     * @param string $dbname
     * @return Pdodb
     */
    public static function iniPdo($dbname = '', $type = 'mysql')
    {
        $dbname = $dbname ?? main['default'][Ips][$type] ?? 'db';
        $conter = main[$type][$dbname];

        return new Pdodb($conter['hosts'], $conter['username'], $conter['password'], $conter['options']);
    }

    /**
     * 初始化redis
     * @param string $type
     * @param string $dbname
     * @param array $attr
     * @return Redisdb
     */
    public static function iniRedis($dbname = '', $type = 'redis', $attr = [])
    {
        $dbname = $dbname ?? main['default'][Ips][$type] ?? 'db';
        $conter = main[$type][$dbname];
        
        return new Redisdb($conter, $attr);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

}