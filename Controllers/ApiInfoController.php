<?php

/**
 * ApiInfo控制器
 * 
 * Class ApiInfoController 控制器
 * 
 * PHP version 7.2
 * 
 * @category Spool
 * @package  ApiInfo
 * @author   yydick Chen <yydick@sohu.com>
 * @license  https://spdx.org/licenses/Apache-2.0.html Apache-2.0
 * @link     http://url.com
 * @DateTime 2021-03-26
 */

namespace Spool\ApiInfo\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Spool\ApiInfo\Requests\ApiInfoContentsRequest;
use Spool\ApiInfo\Services\ApiInfoService;

/**
 * ApiInfo控制器
 * 
 * Class ApiInfoController 控制器
 * 
 * PHP version 7.2
 * 
 * @category Spool
 * @package  ApiInfo
 * @author   yydick Chen <yydick@sohu.com>
 * @license  https://spdx.org/licenses/Apache-2.0.html Apache-2.0
 * @link     http://url.com
 * @DateTime 2021-03-26
 */
class ApiInfoController extends Controller
{
    /**
     * 服务类
     *
     * @var ApiInfoService
     */
    protected $service;
    /**
     * 构造函数初始化
     */
    public function __construct()
    {
        $this->service = new ApiInfoService();
    }
    /**
     * 首页
     * 
     * @return void
     */
    public function index()
    {
        $controllerPath = config("apiinfo.prefix");
        $data = [
            'test' => 'I am test!',
            'controllerPath' => $controllerPath
        ];
        return view('vendor.apiinfo.apiinfo', $data);
    }
    /**
     * 测试信息
     * 
     * @return array
     */
    public function test(): array
    {
        $classes = $this->service->scanRoutes();
        // var_dump($classes);
        // foreach ($classes as $className) {
        //     $doc = $this->service->scanDocument($className);
        //     if (is_string($doc)) {
        //         echo $doc, "<br />\n";
        //     } else if (is_array($doc)) {
        //         var_dump($doc);
        //     }
        // }
        return $classes;
    }
    /**
     * 内容页
     * 
     * @param ApiInfoContentsRequest $request 请求的格式类
     * 
     * @return void
     */
    public function contents(ApiInfoContentsRequest $request)
    {
        $group = $request->group;
        $name = $request->name;
        var_dump($request->all());
        return "content: group is {$group}, name is {$name}!";
    }
}
