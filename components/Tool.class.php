<?php
/**
 * Created by PhpStorm.
 * User: yyswoole
 * Date: 2018/12/3
 * Time: 17:48
 */

namespace components;


class Tool
{

    private static $files = [];

    /**
     * 渲染视图
     * @param $_viewFile_
     * @param null $_data_
     * @param bool $_return_
     * @return string
     */
    public static function render($_viewFile_, $_data_ = null, $_return_ = true)
    {
        if (file_exists($_viewFile_)) {

            //将我们render的参数数组extract为本地变量
            if ($_data_ = &$_data_ && is_array($_data_)) {

                extract($_data_, EXTR_OVERWRITE);
            }

            if ($_return_) {

                //开启缓存输出
                ob_start();
                ob_implicit_flush(false);
                require($_viewFile_);

                return ob_get_clean();
            } else {

                //直接输出
                return require($_viewFile_);
            }
        } else {

            return "Error! {$_viewFile_} not found!";
        }
    }

    /**
     * 获取默认配置
     * @return string
     */
    public static function getDefault()
    {
        self::setConfig('main', true);

        if (isset(main['default']) && is_array(main['default'])) {

        $default = main['default'];
        $ip = swoole_get_local_ip();

        foreach ($ip as $k => &$v) {

            if (isset($default[$v], $default[$v]['domainname'])) {

                defined('Ips') ? "" : define('Ips', $v);
                defined('Port') ? "" : define('Port', $default[$v]['port']);
                defined('DomainName') ? "" : define('DomainName', $default[$v]['domainname']);
            }
        }
    }

    }

    /**
     * 处理提交数据
     * @param $part
     * @return mixed
     */
    public static function handle($part)
    {

        if (is_array($part) && $part = &$part) {

            foreach ($part as $k => $value) {

                $k = self::prep($k);

                if (is_array($value) && $value = &$value) {

                    $part[$k] = self::handle($value);
                } else {

                    $value = self::prep($value);
                    $part[$k] = $value;
                }

            }

            return $part;
        } else {

            return self::prep($part);
        }
    }

    /**
     * 替换内容
     * @param $part
     * @param string $exp
     * @param string $cont
     * @return mixed
     */
    public static function prep($part, $exp = '/[^\w\-\_]/', $cont = '')
    {

        return preg_replace($exp, $cont, $part);
    }

    /**
     * 设置config下的文件为常量
     */
    public static function setConfig($name = '', $define = false, $cfg = S_ROOT . '/config/')
    {

        $config = array_diff(scandir($cfg), ['.', '..']);
        $state = "Without this file!";

        if (is_array($config) && $config =& $config) {

            foreach ($config as &$vl) {

                $fname = explode('.', $vl);

                if (is_array($fname) && $fname =& $fname) {

                    if ($name == $fname[0]) {

                        $cval = include_once $cfg . $vl;
                        $state = ($define && !defined($name)) ? define($fname[0], $cval) : $cval;
                    }
                }
            }
        } else {

            $state = "Empty directory!";
        }

        return $state;
    }

    /**
     * 异步写入文件，无返回值；所以对外直接返回true
     * @param $fname
     * @param $fconten
     * @param int $flags
     * @param bool $section
     */
    public static function setFile($fname, $fconten, $flags = -1, $section = false, $tc = true, $ta = true)
    {
        $fconten = is_string($fconten) ? $fconten : json_encode($fconten);

        $fname = self::catFile($fname, $tc);

        $fconten = $ta ? date('Y-m-d H:i:s ', time()) . $fconten . PHP_EOL : $fconten;

        if ($section) {

            \swoole_async_write($fname, $fconten, $flags, function ($filename) {

                //echo "分段写入 " . $filename . " 成功！" . PHP_EOL;
            });
        } else {

            \swoole_async_writefile($fname, $fconten, function ($filename) {

                //echo "全量写入 " . $filename . " 成功！" . PHP_EOL;
            }, $flags);
        }

        return true;
    }

    /**
     * 读取文件
     * @param $fname
     * @param int $flags
     * @param int $size
     */
    public static function getFile($fname, $flags = -1, $size = 0)
    {
        if ($size) {

            \swoole_async_read($fname, function ($name, $conten) {

                self::$files = ['name' => $name, 'conten' => $conten];
            }, $size, $flags);
        } else {

            \swoole_async_readfile($fname, function ($name, $conten) {

                self::$files = ['name' => $name, 'conten' => $conten];
            });
        }

        return self::$files;
    }

    /**
     * 跟进文件路径判断目录是否存在，不存在则创建
     * @param $fname
     * @param bool $t
     * @return string
     */
    public static function catFile($fname, $t = false)
    {
        $path = explode("/", $fname);
        $name = array_pop($path);
        $name = explode('.', $name);
        $path = implode('/', $path);

        if (!file_exists($path)) {

            mkdir($path, 0777, true);
        }

        return $t ? $path . "/{$name[0]}-" . date('Y-m-d', time()) . ".{$name[1]}" : $fname;
    }
}