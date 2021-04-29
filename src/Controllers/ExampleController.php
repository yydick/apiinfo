<?php

/**
 * Index
 *
 * Class Index
 *
 * PHP version 7.2
 *
 * @category Apiinfo
 * @package  Index
 * @author   yydick Chen <yydick@sohu.com>
 * @license  https://spdx.org/licenses/Apache-2.0.html Apache-2.0
 * @link     http://url.com
 * @DateTime 2021-04-24
 */

namespace Spool\ApiInfo\Controllers;

use App\Http\Controllers\Controller;
use Spool\ApiInfo\Requests\PageRequest;


/**
 * 演示控制器
 *
 * Class ExampleController
 *
 * PHP version 7.2
 *
 * @category Apiinfo
 * @package  Index
 * @author   yydick Chen <yydick@sohu.com>
 * @license  https://spdx.org/licenses/Apache-2.0.html Apache-2.0
 * @link     http://url.com
 * @DateTime 2021-04-24
 */
class ExampleController extends Controller
{
    /**
     * 演示控制器get文档
     *
     * 演示控制器get方法描述
     *
     * 写点什么
     * 接口简介
     *
     * @param PageRequest $request 请求
     *
     * @return  array
     * @version 1.0.0
     * @example {"service":"ALL","qt":581,"content":{"answer":{"song":"你能看到我","album":"是的,我看见了","artist":"啊哈......","pic_url":"http://baidu.com"},"scene":"music","array":[{"log":123}]}} 成功
     * @example {"code":1,"msg":"err"} 失败
     */
    public function getM(PageRequest $request): array
    {
        return ['code' => 0, 'mesg' => 'ok', 'url' => $request->getBaseUrl()];
    }
    /**
     * 首页post文档
     *
     * 演示控制器post方法描述
     *
     * @param PageRequest $request 请求
     *
     * @return  array
     * @version 1.0.0
    //  * @example {"code":0,"msg":"ok"} 成功
    //  * @example {"code":1,"msg":"err"} 失败
     */
    public function postM(PageRequest $request): array
    {
        return [
            'code' => 0, 'mesg' => 'ok',
            'data' => [
                1,
                2,
                3,
                'ss' => ['1', '2', '3']
            ]
        ];
    }
    /**
     * 首页put文档
     *
     * 演示控制器put方法描述
     *
     * @param PageRequest $request 请求
     *
     * @return  array {"code":0,"msg":"ok","data":"12345"}
     * @version 1.0.0
    //  * @example {"code":0,"msg":"ok"} 成功
    //  * @example {"code":1,"msg":"err"} 失败
     */
    public function putM(PageRequest $request): array
    {
        return ['code' => 0, 'mesg' => 'ok'];
    }
    /**
     * 首页delete文档
     *
     * 演示控制器delete方法描述
     *
     * @param PageRequest $request 请求
     *
     * @return    array {"code":0,"msg":"ok"}
     * @version   1.0.0
    //  * @example   {"code":0,"msg":"ok"} 成功
    //  * @example   {"code":1,"msg":"err"} 失败
     */
    public function deleteM(PageRequest $request): array
    {
        return ['code' => 0, 'mesg' => 'ok'];
    }
    /**
     * 首页any文档
     *
     * 演示控制器any方法描述
     *
     * @param PageRequest $request 请求
     *
     * @return  array
     * @version 1.0.0
    //  * @example {"code":0,"msg":"ok"} 成功
    //  * @example {"code":1,"msg":"err"} 失败
     */
    public function anyM(PageRequest $request): array
    {
        return ['code' => 0, 'mesg' => 'ok'];
    }
}
