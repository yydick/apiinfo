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
     * @param ApiInfoContentsRequest $request 请求类
     *
     * @return void
     */
    public function index(ApiInfoContentsRequest $request)
    {
        $trees = $this->service->getDocTree($request);
        $urlAll = $request->url();
        $urlInfo = parse_url($urlAll);
        $url = $urlInfo['scheme'] . '://' . $urlInfo['host'] . '/';
        $modelName = $request->modelName ?? 'default';
        $data = [
            'trees' => $trees,
            'url' => $url,
            'modelName' => $modelName
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
        $doc = $this->service->getDocSearch($request);
        $data = $doc;
        return view('vendor.apiinfo.contents', $data);
    }
    /**
     * 搜索Api
     *
     * @param Request $request 依赖注入
     *
     * @return array
     */
    public function search(Request $request): array
    {
        return [];
    }
}
