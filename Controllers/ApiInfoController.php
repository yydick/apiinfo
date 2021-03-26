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
    public function index(Request $request)
    {
        return view('vendor.apiinfo.apiinfo');
    }
}