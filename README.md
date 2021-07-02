# Lumen Demo

## 运行环境
```
php 版本 >= 7.2.5
```

## 安装部署
```shell
# composer 安装
1、lumen8
$ composer create-project seffeng/lumen-demo

2、lumen7
$ composer create-project seffeng/lumen-demo=7.* --prefer-dist

3、lumen6
$ composer create-project seffeng/lumen-demo=6.* --prefer-dist

# 源码 安装
1、安装
$ composer install -vvv
$ php ./artisan jwt:secret

2、创建数据库；

3、修改 .env 对应配置，如关闭DEBUG(APP_DEBUG=false)、数据库信息（DB_DATABASE）等；

4、执行迁移脚本创建数据表，初始数据，初始用户：（账号：10086, 密码：a123456）；
$ php ./artisan migrate --seed

5、前台根目录为 /public/frontend，后台根目录为 /public/backend， API根目录为 /public/api；

6、增加网站应用；
# 1. /public 目录下增加应用入口，nginx配置root；
# 2. /config/packet.php 增加应用设置；
# 3. /app/Web 目录下增加对应应用；
# 4. /routes 目录下增加对应路由；
# 5. 其他：/storage/framework/views、/resources/views。

7、注意；
# 数据库默认时区为 +00:00，可在 /config/database.php 注释 timezone 或修改 DB_TIMEZONE。

8、数据库账号密码加密配置；
# .env 文件配置参数 APP_CRYPT=false，若为 true 则 .env文件中 数据库账号，数据库密码，redis密码需为加密后的字符；
# 可执行命令 php artisan crypt {原始字符}，如：
# 1、数据账号为 root
# 2、执行命令 php artisan crypt root
# 3、将生成的字符填入 .env 配置 DB_USERNAME=生成字符
# 其他需加密的字符配置参考配置文件 config/database.php
```

## 目录说明
```
├─app
│  ├─Common                 公共模块
│  │  ├─Actions                 公共控制器Action
│  │  ├─Base                    基础接口对象
│  │  ├─Constants               常量定义
│  │  ├─Exceptions              基础异常
|  |  ├─Illuminate              字段值声明
│  │  └─Rules                   自定义验证规则
│  ├─Console
│  │  └─Commands            控制台脚本
│  ├─Exceptions
│  ├─Http
│  │  ├─Middleware
│  ├─Modules                模块管理
│  │  ├─Admin                   管理员
│  │  │  ├─Events                   事件
│  │  │  ├─Exceptions               异常
│  │  │  ├─Illuminate               字段值声明
│  │  │  ├─Listeners                事件监听
│  │  │  ├─Models                   数据表模块
│  │  │  ├─Requests                 表单规则验证
│  │  │  └─Services                 服务处理
│  │  └─User                    用户
│  │      ├─Events                  事件
│  │      ├─Exceptions              异常
│  │      ├─Illuminate              字段值声明
│  │      ├─Listeners               事件监听
│  │      ├─Models                  数据表模块
│  │      ├─Requests                表单规则验证
│  │      └─Services                服务处理
│  ├─Providers
│  └─Web                    WEB应用
│      ├─Backend                后台应用
│      │  ├─Common                  公共模块
│      │  ├─Controllers             控制器
│      │  │  ├─Admin
│      │  │  ├─Auth
│      │  │  ├─Site
│      │  │  └─Test
│      │  └─Requests                表单验证
│      │      └─Admin
│      ├─Api                    API应用
│      │  ├─Common
│      │  │─Controllers
│      │  │  ├─Auth
│      │  │  ├─Site
│      │  │  └─Test
│      │  └─Requests
│      │      └─Auth
│      └─Frontend               前台应用
│          ├─Common
│          ├─Controllers
│          │  ├─Auth
│          │  ├─Site
│          │  └─Test
│          └─Requests
│              └─Auth
├─bootstrap
│  └─cache
├─config
├─database
│  ├─migrations
│  └─seeds
├─public
│  ├─backend                后台入口
│  ├─api                    api入口
│  └─frontend               前台入口
├─resources
│  └─views
│      ├─backend
│      └─frontend
├─routes
├─storage
│  ├─app
│  │  └─public
│  ├─debugbar
│  ├─framework
│  │  ├─cache
│  │  │  └─data
│  │  ├─sessions
│  │  ├─testing
│  │  └─views
│  │      ├─backend
│  │      ├─api
│  │      └─frontend
│  └─logs
└─vendor
```

## 项目依赖

| 依赖                   | 仓库地址                                  | 备注 |
| :--------------------- | :---------------------------------------- | :--- |
| seffeng/lumen-basics   | https://github.com/seffeng/lumen-basics   | 无   |
| fruitcake/laravel-cors | https://github.com/fruitcake/laravel-cors | 无   |
| tymon/jwt-auth         | https://github.com/tymondesigns/jwt-auth  | 无   |

## 演示地址

无

## 备注

无

## 更新日志

[changlog](CHANGELOG.md)

## 已有接口

### api

| 名称     | 地址         | 方式   | 参数              |
| -------- | ------------ | ------ | ----------------- |
| 数据获取 | /down-list   | GET    | type              |
| 登录     | /login       | POST   | username,password |
| 登出     | /logout      | DELETE |                   |
| 是否登录 | /check-login | GET    |                   |
| 登录用户 | /auth        | GET    |                   |
| 修改资料 | /auth        | PUT    | username          |

### backend

| 名称           | 地址             | 方式   | 参数              |
| -------------- | ---------------- | ------ | ----------------- |
| 数据获取       | /down-list       | GET    | type              |
| 登录           | /login           | POST   | username,password |
| 登出           | /logout          | DELETE |                   |
| 是否登录       | /check-login     | GET    |                   |
| 登录用户       | /auth            | GET    |                   |
| 修改资料       | /auth            | PUT    | username          |
| 管理员列表     | /admin           | GET    |                   |
| 管理员添加     | /admin           | POST   | username,password |
| 管理员编辑     | /admin           | PUT    | username,password |
| 管理员删除     | /admin           | DELETE | id                |
| 管理员锁定     | /admin/off       | PUT    | id                |
| 管理员解锁     | /admin/on        | PUT    | id                |
| 管理员登录日志 | /admin/login-log | GET    |                   |
| 操作日志       | /operate-log     | GET    |                   |

### frontend

| 名称     | 地址         | 方式   | 参数              |
| -------- | ------------ | ------ | ----------------- |
| 数据获取 | /down-list   | GET    | type              |
| 登录     | /login       | POST   | username,password |
| 登出     | /logout      | DELETE |                   |
| 是否登录 | /check-login | GET    |                   |
| 登录用户 | /auth        | GET    |                   |
| 修改资料 | /auth        | PUT    | username          |

