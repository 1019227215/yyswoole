![yys](https://github.com/1019227215/yyswoole/blob/master/Public/image/nh.png)  
# yyswoole
##### yyswoole 群：75457153
* 版本要求：
  * `swoole` 4.2.1+
  * `php` 7.0+
* `yyswoole`是基于`swoole`封装的极简框架，支持动静分离，生成静态页面、线上/开发/测试一套代码等
* 框架只是辅助，思路才是王道；再牛逼的框架也挡不住程序`bug`，再牛逼的程序也挡不住`DDOS`，`yyswoole`是一块空地，造车造船任你造
* yyswoole支持热更代码，只需要在get时加上reload=yes即可 项目运行流程如下：
![yys](https://github.com/1019227215/yyswoole/blob/master/Public/image/yys.png)
##### 第一步配置config下的main.php
```php
//根据不同服务器内网ip配置，从而区服不同环境读取配置
'default' => [
        '127.0.0.1' => [//内网ip（linux使用ifconfig获取，使用eth0里的ip）
            'mysql' => 'db',//默认连接mysql数据库配置
            'redis' => 'db',//当前环境默认连接redis配置
            'domainname' => 'www.test.com',//当前环境解析的域名或者ip
            'port' => 80,//当前环境监听的端口
            'safety' => ['chroot' => S_ROOT, 'group' => 'www', 'user' => 'www',],//代码默认目录、起任务进程的用户
        ],

    ],
    
```

##### 第二步启动进程（项目需要跟任务用户同一权限）
```sh
#在项目根目录执行脚本（start|stop|restart）
    sh swoole-manages start
    
    tailf log/http-swoole.log 
    #Swoole http server is started at http://www.你的域名.com:80
    #显示上面内容表示成功！
```

##### 第三步访问网站
* http://你的域名/index.html
* 支持（.php|.html）
* 成功访问表示你可以开始撸了干吧伙计！

### 路由规则
跟yii的驼峰写法一样的命名规则
* 控制器访问方式目录名/文件名/方法名.php或.html结尾访问
1. 控制器必须是首字母大写Controller.class.php结尾（IndexController.class.php）
2. class名称必须与文件名同名（IndexController）并且继承Controller
3. model及其他工具类都以.class.php结尾即可调用。
* 控制器和类都支持无限目录分类

### 静态文件生成
1. 访问html结尾页面不存在时会去找对应的控制器及方法，然后生成html存到对应路径
2. 访问php结尾页面去找对应控制及方法，然后生成html存到对应路径
3. 简单讲就是html只首次更新静态内容，php会每次会更新静态内容
```php
    public function actionIndex()
    {

        return self::renderView("index.php", ['test' => '这是第一次更新！']);
    }
```

## 目录结构
```sh
components        #工具目录
config            #配置目录
controller        #控制器目录
log               #日志目录
model             #model目录（逻辑、数据库、redis访问）
Public            #静态文件目录
swoole.conf       #nginx转发使用此配置文件做转发
swoole-manages    #进程启动、重启、停止
UpDir             #默认上传目录
view              #视图目录
yys-manages.php   #守护进程项目入口

```

