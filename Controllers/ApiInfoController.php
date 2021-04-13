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
     * @param Request $request 请求类
     * 
     * @return void
     */
    public function index(Request $request)
    {
        $trees = $this->service->getDocTree();
        $urlAll = $request->url();
        $urlInfo = parse_url($urlAll);
        // var_dump($urlInfo);
        $url = $urlInfo['scheme'] . '://' . $urlInfo['host'] . '/';
        $data = [
            'trees' => $trees,
            'url' => $url
        ];
        return view('vendor.apiinfo.apiinfo', $data);
    }
    /**
     * Welcome page
     * 
     * @return void
     */
    public function welcome()
    {
        return view('vendor.apiinfo.welcome');
    }
    /**
     * 测试信息
     * 
     * @return array
     */
    public function test(): array
    {
        $routes = $this->service->getDocTree();
        return $routes;
    }
    /**
     * 内容页
     * 
     * @param ApiInfoContentsRequest $request 请求的格式类
     * 
     * @return void
     * 
     * @version 1.0.0
     */
    public function contents(ApiInfoContentsRequest $request)
    {
        $group = $request->group;
        $name = $request->name;
        // var_dump($request->all());
        $doc = $this->service->getDocSearch($request);
        ini_set('xdebug.var_display_max_depth', 6);
        var_dump($doc);
        // return "content: group is {$group}, name is {$name}!";
        $data = $doc;
        return view('vendor.apiinfo.contents', $data);
    }
    /**
     * 搜索Api
     * 
     * @param Request $request 依赖注入
     * @param integer $i       测试变量1
     * @param string  $f       测试变量2
     * 
     * @return array
     * 
     * @example {'code':0,'msg':'success','data':{'total':132,'page':1,'pagesize':10,'list':[{'id':1,'name':'foo'},{'id':2,'name':'bar'}]}} 成功
     * @example location description
     */
    public function search(Request $request, int $i, string $f = 'file'): array
    {
        return [];
    }
}
