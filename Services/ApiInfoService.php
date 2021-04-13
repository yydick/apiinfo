<?php

/**
 * ApiInfo服务组件
 *
 * Class ApiInfoService 服务组件
 *
 * PHP version 7.2
 *
 * @category Spool
 * @package  ApiInfo
 * @author   yydick Chen <yydick@sohu.com>
 * @license  https://spdx.org/licenses/Apache-2.0.html Apache-2.0
 * @link     http://url.com
 * @DateTime 2021-03-27
 */

namespace Spool\ApiInfo\Services;

use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
// use Illuminate\Support\Facades\Route;
// use Illuminate\Routing\Route;
use Illuminate\Foundation\Http\FormRequest;
use Route;
use Spool\ApiInfo\Requests\ApiInfoContentsRequest;

/**
 * ApiInfo服务组件
 *
 * Class ApiInfoService 服务组件
 *
 * PHP version 7.2
 *
 * @category Spool
 * @package  ApiInfo
 * @author   yydick Chen <yydick@sohu.com>
 * @license  https://spdx.org/licenses/Apache-2.0.html Apache-2.0
 * @link     http://url.com
 * @DateTime 2021-03-27
 */
class ApiInfoService
{
    /**
     * 扫描控制器路径, 并且扫描子目录
     *
     * @param string $path 要扫描的路径
     *
     * @return array
     */
    public function scanControllerAndSubDirectory(string $path = ''): array
    {
        return [];
    }
    /**
     * 扫描控制器路径
     *
     * @param string $path 要扫描的路径
     *
     * @return array
     */
    public function scanController(string $path = ''): array
    {
        $controllerPath = $path ?: config("apiinfo.implementsPath");
        $files = scandir(app_path() . $controllerPath);
        $objs = [];
        foreach ($files as $key => $file) {
            $filename = explode('.', $file);
            $fileExtension = end($filename);
            if ($fileExtension != 'php' || $file == 'Controller.php') {
                unset($files[$key]);
                continue;
            }
            $files[$key] = str_replace('.php', '', $file);
            $className = config("apiinfo.namespace") . $files[$key];
            $objs[] = $className;
        }
        // return array_values($files);
        return $objs;
    }
    /**
     * 扫描子目录
     *
     * @param string $path 要扫描的路径
     *
     * @return array
     */
    public function scanSubDirectory(string $path = ''): array
    {
        $controllerPath = $path ?: config("apiinfo.implementsPath");
        $files = scandir(app_path() . $controllerPath);
        foreach ($files as $key => $path) {
            if ($path === '.' || $path === '..' || filetype($path) != 'dir') {
                unset($files[$key]);
            }
        }
        return array_values($files);
    }
    /**
     * 扫描路由
     *
     * @param string $modelName 接口组名称
     *
     * @return array
     */
    public function scanLaravelRoutes(string $modelName = 'default'): array
    {
        /**
         * @var \Illuminate\Routing\RouteCollection
         */
        $baseRoutes = Route::getRoutes();
        $routes = $baseRoutes->getRoutes();
        $routess = [];

        $configPrefix = config("apiinfo.{$modelName}.prefix");

        foreach ($routes as $route) {
            // var_dump($route);
            $action = $route->getAction();
            $method = $route->methods;
            $uri = $route->uri;
            $prefix = $action['prefix'] ?: '';
            if (
                !isset($action['controller']) ||
                \substr($uri, 0, strlen('_ignition')) === '_ignition'
            ) {
                continue;
            }
            if ($configPrefix) {
                if (
                    is_string($configPrefix) &&
                    (!$prefix ||
                        $prefix != $configPrefix)
                ) {
                    continue;
                } elseif (is_array($configPrefix)) {
                    if (!in_array($prefix, $configPrefix, true)) {
                        continue;
                    }
                }
            }
            $routess[] = [
                'uri' => $uri,
                'controller' => $action['controller'],
                'method' => $method,
                'prefix' => $prefix,
                'namespace' => $action['namespace'] ?? null
            ];
        }
        return $routess;
    }
    /**
     * 扫描控制器生产文档
     *
     * @param string $className 控制器类名
     * @param string $method    方法名
     *
     * @return string
     */
    public function scanDocument(string $className, string $method = ''): string
    {
        $doc = '';
        try {
            $rc = $method ?
                new ReflectionMethod($className, $method) :
                new ReflectionClass($className);
            $doc = $rc->getDocComment();
        } catch (\ReflectionException $e) {
            throw $e;
        }
        return $doc;
    }
    /**
     * 返回文档左侧树图
     * 框架默认为laravel, 后续会支持更多框架
     *
     * @param string $framework 框架名称
     *
     * @return array
     */
    public function getDocTree(string $framework = ''): array
    {
        $tree = $routes = [];
        $routes = $this->getDocSearch(null, $framework);
        foreach ($routes as $routeInfo) {
            $treeGroupName = $routeInfo['treeGroupName'];
            $treeApiName = $routeInfo['treeApiName'];
            $tree[$treeGroupName][$treeApiName] = $routeInfo;
        }
        // $tree = $routes;
        return $tree;
    }
    /**
     * 搜索接口信息
     *
     * @param ?ApiInfoContentsRequest $request   要搜索的关键字
     * @param string                  $framework 使用的框架
     *
     * @return array
     */
    public function getDocSearch(
        ?ApiInfoContentsRequest $request,
        string $framework = ''
    ): array {
        $group = $request->group ?? '';
        $name = $request->name ?? '';
        $routes = [];
        if (!$framework || 'laravel' == strtolower($framework)) {
            $routes = $this->scanLaravelRoutes();
        }
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
            $doc = $this->scanDocument($className);
            $classDoc = array_values(array_filter(explode('*', str_replace([' ', "\n", "/"], '', $doc))));
            $classGroup = explode('\\', $className);
            $subDoc = $this->scanDocument($className, $methodName);
            $methodDoc = array_values(array_filter(explode('*', str_replace(["\n", "/"], '', $subDoc)), "trim"));
            // echo "{$subDoc}\n";
            // var_dump($methodDoc);
            $apiGroup = $apiName = '';
            foreach ($methodDoc as $keyMethod => $value) {
                if (!$keyMethod) {
                    continue;
                }
                $mDoc = trim($value);
                if (substr($mDoc, 0, 9) == '@apiGroup') {
                    $groupTmp = array_filter(explode(' ', $mDoc));
                    $apiGroup = $groupTmp[1];
                }
                if (substr($mDoc, 0, 8) == '@apiName') {
                    $nameTmp = array_filter(explode(' ', $mDoc));
                    $apiName = $nameTmp[1];
                }
            }
            $params = $this->getMethodParams($className, $methodName);
            $routes[$key]['className'] = $className;
            $routes[$key]['classDoc'] = $classDoc[0];
            $routes[$key]['classGroup'] = end($classGroup);
            $routes[$key]['methodName'] = $methodName;
            $routes[$key]['methodDoc'] = trim($methodDoc[0]);
            $routes[$key]['apiGroup'] = $apiGroup;
            $routes[$key]['apiName'] = $apiName;
            $routes[$key]['treeGroupName'] = $apiGroup ?: $classDoc[0];
            $routes[$key]['treeApiName'] = $apiName ?: $methodName;
            $routes[$key]['params'] = $params['params'];
            $routes[$key]['return'] = $params['return'];
            $routes[$key]['docParams'] = $params['docParams'];
            $routes[$key]['docReturn'] = $params['docReturn'];
            $routes[$key]['docExample'] = $params['docExample'];
            $routes[$key]['docVersion'] = $params['docVersion'];
            if (
                $group &&
                $name &&
                $routes[$key]['classGroup'] == $group &&
                $methodName == $name
            ) {
                return $routes[$key];
            }
        }
        return $routes;
    }
    /**
     * 获取类方法的参数和返回值
     * 
     * @param string $className  类名
     * @param string $methodName 方法名
     * 
     * @return array
     */
    public function getMethodParams(string $className, string $methodName): array
    {
        $data = $params = $docParams = $docExample = [];
        $returnType = $docReturnType = $docVersion = '';
        $reflection = new ReflectionMethod($className, $methodName);
        $params = $reflection->getParameters();
        $returnType = $reflection->hasReturnType() ? $reflection->getReturnType()->getName() : 'void';
        $subDoc = $this->scanDocument($className, $methodName);
        $methodDoc = array_values(array_filter(explode('*', str_replace(["\n", "/"], '', $subDoc)), "trim"));
        foreach ($methodDoc as $index => $doc) {
            $docTmp = [];
            $docArray = array_values(array_filter(explode(' ', trim($doc)), "trim"));
            if ($docArray[0] == '@param') {
                // var_dump($docArray);
                $docTmp['name'] = substr($docArray[2], 1);
                $docTmp['type'] = $docArray[1];
                $docTmp['doc'] = $docArray[3];
                $docTmp['form'] = 'form';
                $docParams[$docTmp['name']] = $docTmp;
                continue;
            }
            if ($docArray[0] == '@return') {
                $docReturnType = $docArray[1];
            }
            if ($docArray[0] == '@example') {
                $exampleTmp['location'] = $docArray[1];
                $exampleTmp['description'] = $docArray[2];
                $docExample[] = $exampleTmp;
            }
            if ($docArray[0] == '@version') {
                $docVersion = $docArray[1];
            }
        }
        $data['docParams'] = $docParams;
        $data['docReturn'] = $docReturnType;
        $data['docExample'] = $docExample;
        $data['docVersion'] = $docVersion;
        $data['return'] = $returnType;
        $data['params'] = [];
        /**
         * @var ReflectionParameter
         */
        foreach ($params as $key => $value) {
            $tmp = [];
            $tmp['name'] = $value->name;
            $type = $value->hasType() ? $value->getType()->getName() : 'mixed';
            $tmp['type'] = $type;
            $tmp['form'] = 'form';
            $tmp['required'] = 'required';
            $tmp['doc'] = '';
            if ($value->isDefaultValueAvailable()) {
                $tmp['defaultValue'] = $value->getDefaultValue();
                $tmp['required'] = 'nullable';
            }
            if (!in_array($type, [
                'int', 
                'string',
                'bool',
                'folat',
                'mixed'
            ], true)) {
                $objParams = $this->getClassProperties($type);
                $data['params'] = array_merge($data['params'], $objParams);
                continue;
            } else {
                $tmp['form'] = 'path';
            }
            $data['params'][$tmp['name']] = $tmp;
        }
        // var_dump($className, $methodName, $params, $docParams);
        return $data;
    }
    /**
     * 返回类的属性列表
     * 
     * @param string $className 要查的类名
     * 
     * @return array
     */
    public function getClassProperties(string $className): array
    {
        $data = [];
        $reflect = new ReflectionClass($className);
        $obj = new $className();
        // var_dump($obj instanceof FormRequest, $className);
        if ($obj instanceof FormRequest) {
            $rules = $reflect->hasMethod('rules') ? $obj->rules() : [];
            $messages = $reflect->hasMethod('messages') ? $obj->messages() : [];
            // var_dump($rules, $messages);
            foreach ($rules as $key => $value) {
                $tmp['name'] = $key;
                $type = 'mixed';
                if (stripos($value, 'string') !== false) {
                    $type = 'string';
                } elseif (stripos($value, 'int') !== false) {
                    $type = 'int';
                } elseif (stripos($value, 'numeric') !== false) {
                    $type = 'numeric';
                } elseif (stripos($value, 'file') !== false) {
                    $type = 'file';
                }
                $tmp['type'] = $type;
                $tmp['form'] = 'form';
                $tmp['required'] = 'required';
                if (stripos($value, 'nullable') !== false) {
                    $tmp['required'] = 'nullable';
                }
                $tmp['doc'] = $messages[$key] ?? '';
                $data[$tmp['name']] = $tmp;
            }
        }
        return $data;
    }
}
