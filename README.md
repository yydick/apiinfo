# apiinfo

#### 介绍
PHP框架API文档生成工具, 无需生成静态文档. 动态生成. 目前完成了基于Laravel的实现. 

#### 软件架构
软件架构说明


#### 安装教程

1.  Composer安装

>我本人的开发环境是PHP7.3, 目前还没有做向下兼容的测试

```
composer require spool/apiinfo
```
>运行这个命令添加资源和配置
```
php artisan vendor:publish --tag=apiinfo
```
打开 http://localhost/apiinfo/ 即可看到演示页面

#### 使用说明

1.  配置说明
>framework定义使用的框架, 目前只支持laravel, 以后会增加对其他框架的支持
>default是要扫描的路由前缀分组, 如针对不同的前端有不同的api文档,可以定义多个
>>prefix定义该分组要扫描的路径前缀, 如果有多个前缀可以定义多个, 都会包含在一个文档页面里
2.  配置代码
```
//ApiInfo配置文件
return [
    'framework' => 'laravel',
    'default' => [
        /**
         * 要扫描的路由前缀
         */
        'prefix' => ['exampleApiinfo'],
    ],
    'default1' => [
        /**
         * 要扫描的路由前缀
         */
        'prefix' => ['api'],
    ],
];
```
3.  页面模板
>路径在 /resources/views/vendor/apiinfo 可以修改. 另希望能有前端贡献好看的页面模板

4.  文档生成
>样例控制器在 vendor/spool/apiinfo/src/Controllers/ExampleController.php 里面, 可以参照这个控制器来编写文档.
>
>为了便于文档生成, 提供了Spool\ApiInfo\Requests\BaseRequest Spool\ApiInfo\Requests\PageRequest两个请求类, 作为控制器的参数.

5.  对比Swagger和ApiDoc

+ 每次修改后不需要生成静态页面, 实时修改, 实时更新, 不会因为忘记修改导致文档过时.
+ 不需要添加大量的注释来辅助文档生成, 只需填写几个常用的注释即可.
+ 完全基于PHP实现, 源代码可控.

#### 注释说明:

~~~
/**
 * 演示控制器 -- 分组名称
 */
class ExampleController extends Controller
{
    /**
     * 演示控制器get文档 -- 接口名称
     *
     * 演示控制器get方法描述 -- 接口介绍
     * 写点什么
     * 接口简介
     *
     * @param PageRequest $request 请求 -- 参数类
     *
     * @return  array
     * @version 1.0.0 -- 接口版本号
     * @example {"service":"ALL","qt":581,"content":{"answer":{"song":"你能看到我","album":"是的,我看见了","artist":"啊哈......","pic_url":"http://baidu.com"},"scene":"music","array":[{"log":123}]}} 成功 -- 接口返回值示例json格式
     * @example {"code":1,"msg":"err"} 失败 -- 接口返回值示例json格式
     */
    public function getM(PageRequest $request): array
    {
        return ['code' => 0, 'mesg' => 'ok', 'url' => $request->getBaseUrl()];
    }
}
~~~

#### 参与贡献

1.  Fork 本仓库
2.  新建 Feat_xxx 分支
3.  提交代码
4.  新建 Pull Request


#### 特技

1.  使用 Readme\_XXX.md 来支持不同的语言，例如 Readme\_en.md, Readme\_zh.md
2.  Gitee 官方博客 [blog.gitee.com](https://blog.gitee.com)
3.  你可以 [https://gitee.com/explore](https://gitee.com/explore) 这个地址来了解 Gitee 上的优秀开源项目
4.  [GVP](https://gitee.com/gvp) 全称是 Gitee 最有价值开源项目，是综合评定出的优秀开源项目
5.  Gitee 官方提供的使用手册 [https://gitee.com/help](https://gitee.com/help)
6.  Gitee 封面人物是一档用来展示 Gitee 会员风采的栏目 [https://gitee.com/gitee-stars/](https://gitee.com/gitee-stars/)
