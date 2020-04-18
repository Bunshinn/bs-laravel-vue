
参考[laravel+vue开发环境搭建
](https://www.jianshu.com/p/1c2cc11ba46f)

## 安装php
[官网下载](https://windows.php.net/downloads/releases/php-7.3.17-nts-Win32-VC15-x64.zip)并解压安装，并设置环境变量

## 安装composer
php目录下运行：
```
php -r "readfile('https://getcomposer.org/installer');" | php
echo @php "%~dp0composer.phar" %*>composer.bat

# 查看composer状态, 显示版本信息安装成功
composer -V
# 切换目录使用相对路径查看, 显示一致
cd ..
.\php\composer -V

# 修改镜像源 
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
# composer config -g repo.packagist composer https://packagist.phpcomposer.com
```  
或下载[composer安装包](https://getcomposer.org/Composer-Setup.exe)引导安装

[修改镜像源](https://pkg.phpcomposer.com/) 
[参考博文](https://blog.csdn.net/qq_33182756/article/details/79948384)

### Warning: readfile(): Unable to find the wrapper "https" - did you forget to enable it when you configured PHP? in Command line code on line 1

windows下解决方案：  
需要到php.ini中把`extension=php_openssl`前面的`;`删掉，
`extension=pdo_mysql`前面的`;`删掉，
extension_dir = "ext"前面的`;`删掉，

重启服务就可以了。  
如没有`php.ini`文件,可复制`php.ini-development`文件并改名.

### composer安装时无法连接github

使用windows下ubuntu的ssh.exe，生成公钥私钥。
<https://www.jianshu.com/p/393c5a6efdad>

下载中需要调用git, 下载安装之. 阿里镜像加速下载
<https://npm.taobao.org/mirrors/git-for-windows/v2.26.0.windows.1/>


## 安装laravel
安装laravel需要用到composer, 这里php未设置环境变量，使用相对路径引用  
```
# composer位于php目录下
.\php\composer create-project --prefer-dist laravel/laravel laravel+vue "5.5.*" --ignore-platform-reqs

# 显示如下信息安装成功
@php artisan key:generate
Application key [base64:xxxxxxxxxxxxxxxxxxxxxxxx] set successfully.
```
如安装过程无报错结束，则可启动laravel。
```
# 调用php启动服务，这里php未设置环境变量，使用相对路径引用
..\php\php.exe artisan serve
# 显示如下，在浏览器打开链接即可看到启动成功页面
Laravel development server started: <http://127.0.0.1:8000>
```


## 安装nodejs & npm & cnpm

使用国内镜像<https://npm.taobao.org/mirrors/node/>
下载并安装[nodejs](https://cdn.npm.taobao.org/dist/node/v12.16.2/node-v12.16.2-x64.msi),按默认引导安装完成。

### [安装cnpm](https://developer.aliyun.com/mirror/NPM?from=tnpm)
原镜像下载较慢，设置为国内镜像
```
# 查看并修改npm镜像源
npm config list
npm set registry https://registry.npm.taobao.org/

# 安装cnpm
npm install -g cnpm --registry=https://registry.npm.taobao.org

# 使用cnpm安装
cnpm install 

# 不报错,安装成功
npm run dev
# 启动实时监控运行
npm run watch
```

安装过程中,使用npm安装始终不成功, 最后转到cnpm一次就成功了. 可能是npm的源有问题或者部分网络不通.

npm安装有踩坑, 尝试了各种方法, 最终也都并不能成功:  
安装编译工具`npm install --global --production windows-build-tools`  
```
# before installing node-gyp on windows
npm install --global --production windows-build-tools
 
# install node-gyp globally
npm install -g node-gyp
```
安装cross-env: `npm install cross-env --save-dev`
[参考](https://www.cnblogs.com/phpper/p/6781426.html)

清理缓存： `npm cache clean --force` 



## larvavel-vue目录架构

### 修改一下webpack.mix.js文件
添加一个代理端口
```
mix.browserSync({ proxy: 'localhost:8000'});
```
修改后需要安装
```
cnpm install browser-sync
cnpm install browser-sync-webpack-plugin
```
### resource/views 目录下的 welcome.blade.php  
在vue项目中我们需要一个app.js作为启动入口。
这个app.js在laravel中项目已经为我们建好了，
在/resources/assets/js下面，我们只需要把他引过来  
`<script src="{{ mix('js/app.js') }}"></script>` 或者  
`<script type="text/javascript" src="/js/app.js"></script>`  
推荐使用前者，且需要置于文件尾部。  
我们还需要aap.css，也在/resources/assets/css下  
`<link rel="stylesheet" type="text/css" href="/css/app.css">`  

修改后welcome.blade.php文件如下：
```
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/css/app.css">
        <!-- Styles -->

    </head>
    <body>
    <div id="app">
        <router-view></router-view> 
    </div>
    </body>
    <script src="{{ mix('js/app.js') }}"></script>

</html>
```

### app.js

/resources/assets/js/app.js
```
require('./bootstrap');
window.Vue = require('vue');
Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app',
    template: "<div>hello wrold</div>"
});
```

### 运行npm run watch开启服务

```
npm run dev
# 若npm安装时显示正常, 而此处异常显示如下, 参考前`修改一下webpack.mix.js文件`部分安装依赖
Additional dependencies must be installed. This will only take a moment.

npm run watch
# 显示如下运行成功
[Browsersync] Proxying: http://localhost:8000
[Browsersync] Access URLs:
 -------------------------------------
       Local: http://localhost:3000
    External: http://172.27.25.13:3000
 -------------------------------------
          UI: http://localhost:3001
 UI External: http://localhost:3001
 -------------------------------------
[Browsersync] Watching files...
[Browsersync] Reloading Browsers...
```

## vue-router路由管理

### 安装依赖
```
cnpm install vue-router
```

### 配置路由

1. 设置Index.vue模板
```
# resources\assets\js\components\Index.vue

<template>
    <div class="container">
        <h1> This is from Index.vue </h1>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
```

2. route.js
```
# resources\assets\js\route.js

const routes = [
  { 
    path: '/', //uri
    component:  require('./components/Index.vue') // 对应组件
   }
];
export default routes;
```

3. app.js
```
require('./bootstrap');
window.Vue = require('vue');
import VueRouter from 'vue-router';
Vue.use(VueRouter);
import routes from './route';    // 路由配置文件

// 实例化路由
const router = new VueRouter({
    mode:'history',
    routes
})

var vm = new Vue({
    router
}).$mount('#app');
```

## laravel model+controller设计
修改.env文件,链接好本地数据库
确认php.ini配置文件的`extension=pdo_mysql`前面的`;`已删掉，  
在app目录下面创建Models文件夹，在Models下面创建一个user类

#### app\Models\User.php

```
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class User extends Model
{
    protected $table = 'user';
    protected $guarded = [];
    // 测试类
    public function  getInfo(){
        $users = self::get();
        return $users;
    }
}
```

#### app\Http\Controllers\UserController.php
```
<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Routing\Controller;
class UserController extends Controller
{
    public function __construct()
    {
        $this->objUser = new User();
    }
    public function getUserList(){
        return $this->objUser->getInfo();
    }
}
```

#### routes\api.php
```
<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::any('/getUserList','UserController@getUserList');
```

#### 创建数据库表
```
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `account` varchar(255) DEFAULT NULL COMMENT '账号',
  `password` varchar(255) DEFAULT NULL COMMENT '用户密码',
  `six` int(2) DEFAULT NULL COMMENT '性别 0 男 1 女',
  `address` varchar(255) DEFAULT NULL COMMENT '用户地址',
  `phone` varchar(255) DEFAULT NULL COMMENT '手机号',
  `age` int(255) DEFAULT NULL COMMENT '年龄',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `status` int(255) DEFAULT NULL COMMENT '状态',
  `isDelete` varchar(255) DEFAULT NULL COMMENT '是否被删除 0 没被删除 1 被删除',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `update_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('20', 'test', 'test', '202cb962ac59075b964b07152d234b70', '1', 'sdf', '17777777777', '25', '25@qq.com', '1', '0', null, null, null);

```

#### 查看结果
localhost:8000/api/getUserList


## axios+vuex配合获取后台数据渲染页面

#### 安装依赖包
```
cnpm install vuex
cnpm install axios
```

#### resources\assets\js\store\index.js
```
import Vue from 'vue';
import Vuex from 'vuex';
import user from './user';
Vue.use(Vuex);
export default new Vuex.Store({
    // 可以设置多个模块
    modules: {
        user
    }
});
```
#### resources\assets\js\store\user.js
```
import api from '../api';
export default{
    state: {
        user: []
    },
    mutations: {
        // 注意，这里可以设置 state 属性，但是不能异步调用，异步操作写到 actions 中
        SETUSER(state, lists) {
            state.user = lists;
        }
    },
    actions: {
        getUser({commit}) {
            api.getUser().then(function(res) {
                //console.log(res);
                commit('SETUSER', res.data);
            });
        }
    }
}
```

#### resources\assets\js\api.js
```
import axios from 'axios'
export default {
    // 首页接口
    getUser: function (params) {
        return axios.get('api/getUserList')
    },
}
```


#### resources\assets\js\components\Index.vue
```
<template>
    <div>
        1
       <ul class="list-group">
            <li class="list-group-item"
                v-for="item in user">
                    {{ item.account }}
            </li>
        </ul>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex';
    export default {
        name: "App",
        // 映射 vuex 上面的属性
        computed: mapState({
            user: state => state.user.user
        }),
        created() {
            this.getUser();
            //console.log(this.$store.state);
        },
       methods: {
            // 映射 vuex 对象上的方法
            ...mapActions([
                'getUser'
            ])
        }
    }
</script>
<style scoped>
</style>
```

#### app.js
```
require('./bootstrap');
window.Vue = require('vue');
import VueRouter from 'vue-router';
Vue.use(VueRouter);
import routes from './route';    // 路由配置文件
import store from './store/'; // vuex 数据存储所需对象

// 实例化路由
const router = new VueRouter({
    mode:'history',
    routes
})

var vm = new Vue({
    store,
    router
}).$mount('#app');
```

#### 刷新页面显示成功