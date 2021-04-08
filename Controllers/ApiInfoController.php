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
        $routes = $this->service->scanLaravelRoutes();
        foreach ($routes as $key => $route) {
            $controller = $route['controller'];
            if (is_array($controller)) {
                $className = get_class($controller[0]);
                $methodName = $controller[1];
            } elseif (is_string($controller)) {
                $separator = '@';
                if (stripos('::', $controller) != false) {
                    $separator = '::';
                }
                $callback = explode($separator, $controller);
                $className = $callback[0];
                $methodName = $callback[1];
            }
            $doc = $this->service->scanDocument($className);
            $classDoc = array_values(array_filter(explode('*', str_replace([' ', "\n", "/"], '', $doc))));
            // echo $classDoc[0], "\n";
            // var_dump($classDoc);
            $routes[$key]['className'] = $className;
            $routes[$key]['classDoc'] = $classDoc[0];
            $subDoc = $this->service->scanDocument($className, $methodName);
            $methodDoc = array_values(array_filter(explode('*', str_replace([' ', "\n", "/"], '', $subDoc))));
            // echo "{$subDoc}\n";
            // var_dump($methodDoc);
            $routes[$key]['methodName'] = $methodName;
            $routes[$key]['methodDoc'] = $methodDoc[0];
        }
        return $routes;
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
