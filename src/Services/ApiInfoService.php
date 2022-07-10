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
    public function scanDingoRoutes(string $modelName = 'default'): array
    {
        $baseRoutes = app('Dingo\Api\Routing\Router');
        // $routes = $baseRoutes->getRoutes();
        $routes = [];
        $prefixConfig = config("apiinfo.{$modelName}.prefix");
        foreach ($baseRoutes->getRoutes() as $collection) {
            foreach ($collection->getRoutes() as $route) {
                $uri = $route->uri();
                if (!$uri) {
                    continue;
                }
                $prefixExplode = explode('/', $uri);
                if (!isset($prefixExplode[0])) {
                    continue;
                }
                $prefix = $prefixExplode[0];
                if (is_array($prefixConfig) && !in_array($prefix, $prefixConfig)) {
                    continue;
                }
                if (\is_string($prefixConfig) && $prefix != $prefixConfig) {
                    continue;
                }
                $versionImplode = $route->versions();
                if (!$versionImplode) {
                    continue;
                }
                $routes[] = [
                    // 'host' => $route->domain(),
                    'method' => implode('|', $route->methods()),
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'controller' => $route->getActionName(),
                    'prefix' => $prefix,
                    'protected' => $route->isProtected() ? 'Yes' : 'No',
                    'versions' => implode(', ', $route->versions()),
                    'scopes' => implode(', ', $route->scopes()),
                ];
            }
        }
        return $routes;
    }
    /**
     * 扫描Laravel路由
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
            $action = $route->getAction();
            $methods = count($route->methods);
            $method = 'GET';
            if ($methods == 1) {
                $method = $route->methods[0];
            } elseif ($methods > 3) {
                $method = "ANY";
            }
            $uri = $route->uri;
            $prefix = explode('/', $uri)[0];
            if (
                !isset($action['controller']) ||
                \substr($uri, 0, strlen('_ignition')) === '_ignition'
            ) {
                continue;
            }
            if ($configPrefix) {
                if (
                    is_string($configPrefix) &&
                    (!$prefix || $prefix != $configPrefix)
                ) {
                    continue;
                }
                if (is_array($configPrefix)) {
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
     * @param ApiInfoContentsRequest $request 请求的类
     *
     * @return array
     */
    public function getDocTree(ApiInfoContentsRequest $request): array
    {
        $tree = $routes = [];
        $routes = $this->getDocSearch($request);
        foreach ($routes as $routeInfo) {
            $treeGroupName = $routeInfo['treeGroupName'];
            $treeApiName = $routeInfo['treeApiName'];
            $tree[$treeGroupName][$treeApiName] = $routeInfo;
        }
        return $tree;
    }
    /**
     * 搜索接口信息
     *
     * @param ApiInfoContentsRequest $request   要搜索的关键字
     *
     * @return array
     */
    public function getDocSearch(
        ApiInfoContentsRequest $request
    ): array {
        $group = $request->group ?? '';
        $name = $request->name ?? '';
        $modelName = $request->modelName ?? 'default';
        $routes = [];
        $framework = \config('apiinfo.framework');
        if ('dingo' == \strtolower($framework)) {
            $routes = $this->scanDingoRoutes($modelName);
        } else {
            $routes = $this->scanLaravelRoutes($modelName);
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
            $classGroup = explode('\\', $className);
            if (
                ($group && end($classGroup) != $group) ||
                ($name && $methodName != $name)
            ) {
                continue;
            }
            $doc = $this->scanDocument($className);
            $classDoc = array_values(
                array_filter(
                    explode(
                        '*',
                        str_replace(
                            [' ', "\n", "/"],
                            '',
                            $doc
                        )
                    )
                )
            );
            $subDoc = $this->scanDocument($className, $methodName);
            $methodDoc = array_values(array_filter(explode('*', str_replace(["\n", "/"], '', $subDoc)), "trim"));
            // echo "{$subDoc}\n";
            // dd($methodDoc);
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
            $routes[$key]['classDoc'] = $classDoc[0] ?? '';
            $routes[$key]['classGroup'] = end($classGroup);
            $routes[$key]['methodName'] = $methodName;
            $routes[$key]['methodDoc'] = trim($methodDoc[0] ?? '') ?: $methodName;
            $routes[$key]['apiGroup'] = $apiGroup ?: end($classGroup);
            $routes[$key]['apiName'] = $apiName ?: $methodName;
            $routes[$key]['treeGroupName'] = $apiGroup ?: $classDoc[0] ?? '';
            $routes[$key]['treeApiName'] = $apiName ?: $methodName;
            $routes[$key]['params'] = $params['params'];
            $routes[$key]['return'] = $params['return'];
            $routes[$key]['returnDoc'] = $params['returnDoc'];
            $routes[$key]['description'] = $params['description'];
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
        $returnType = $docReturnType = $docReturnDoc = $docVersion = '';
        $reflection = new ReflectionMethod($className, $methodName);
        $params = $reflection->getParameters();
        $returnType = $reflection->hasReturnType() ? $reflection->getReturnType()->getName() : 'void';
        $subDoc = $this->scanDocument($className, $methodName);
        // \var_dump($subDoc);
        $methodDoc = array_values(array_filter(explode('*', str_replace(["\n", "/"], '', $subDoc)), "trim"));
        $description = '';
        foreach ($methodDoc as $index => $doc) {
            $docTmp = [];
            $docArray = array_values(array_filter(explode(' ', trim($doc)), "trim"));
            if ($docArray[0] == '@param') {
                // var_dump($docArray);
                $docTmp['name'] = substr($docArray[2], 1);
                $docTmp['type'] = $docArray[1] ?? '';
                $docTmp['doc'] = $docArray[3] ?? '';
                $docTmp['form'] = 'form';
                $docParams[$docTmp['name']] = $docTmp;
                continue;
            }
            if ($docArray[0] == '@return') {
                $docReturnType = $docArray[1] ?? 'void';
                $docReturnDoc = $docArray[2] ?? '{"Document location":"@return mixed doc","format":"json"}';
                continue;
            }
            if ($docArray[0] == '@example') {
                $exampleTmp['location'] = $docArray[1];
                $exampleTmp['description'] = $docArray[2];
                $docExample[] = $exampleTmp;
                continue;
            }
            if ($docArray[0] == '@version') {
                $docVersion = $docArray[1];
                continue;
            }
            if ($index) {
                $description .= trim($doc) . "<br/>\n";
            }
        }
        $data['docParams'] = $docParams;
        $data['docReturn'] = $docReturnType;
        $data['docExample'] = $docExample;
        $data['docVersion'] = $docVersion;
        $data['return'] = $returnType;
        $data['returnDoc'] = $docReturnDoc;
        $data['description'] = $description;
        $data['params'] = [];
        /**
         * @var ReflectionParameter
         */
        foreach ($params as $key => $value) {
            $tmp = [];
            $tmp['name'] = $value->name;
            $type = $value->hasType() ? $value->getType()->getName() : 'mixed';
            if ($type == 'mixed' && $docParams[$value->name]['type']) {
                $type = $docParams[$value->name]['type'];
            }
            $tmp['type'] = $type;
            $tmp['form'] = 'form';
            $tmp['required'] = 'required';
            $tmp['doc'] = '';
            $tmp['defaultValue'] = null;
            if ($value->isDefaultValueAvailable()) {
                $tmp['defaultValue'] = $value->getDefaultValue();
                $tmp['required'] = 'nullable';
            }
            if (!in_array($type, [
                'int',
                'string',
                'bool',
                'folat',
                'mixed',
                'array'
            ], true)) {
                $objParams = $this->getClassProperties($type);
                $data['params'] = array_merge($data['params'], $objParams);
                continue;
            } else {
                $tmp['form'] = 'path';
            }
            $data['params'][] = $tmp;
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
            $defaultValue = $reflect->hasMethod('getDefaultValue') ? $obj->getDefaultValue() : [];
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
                $tmp['defaultValue'] = $defaultValue[$key] ?? '无';
                $data[] = $tmp;
            }
        }
        return $data;
    }
    /**
     * 格式化json字符串
     *
     * @param array $arr 要格式化的数组
     *
     * @return string
     */
    public function getJsonFormatArray(array $arr): string
    {
        \var_dump($arr);
        $jsonStr = json_encode(
            $arr,
            \JSON_UNESCAPED_UNICODE
        );
        // \var_dump($jsonStr);
        $format = $this->getJsonFormatString($jsonStr);
        return $format;
    }
    /**
     * 格式化json字符串
     *
     * @param string $string 要格式化的字符串
     *
     * @return string
     */
    public function getJsonFormatString(string $string): string
    {
        $format = \str_replace('{', "{\n", $string);
        $format = \str_replace('[', "[\n", $format);
        $format = \str_replace(',', ",\n", $format);
        $format = \str_replace('\"', "\"", $format);
        $format = \str_replace(']', "]\n", $format);
        $format = \str_replace('}', "}\n", $format);
        $format = \str_replace('\/', "\\", $format);
        $format = \str_replace('\'', "\"", $format);
        \var_dump($format, $string);
        return $format;
    }
    /**
     * 将字符串格式化为可是胡的json
     *
     * @param string $str 要格式化的json字符串
     *
     * @return string
     */
    public function jsonFormatByString(string $str): array
    {
        $arr = json_decode($str, true);
        if (!$arr) {
            return [];
        }
        return $this->jsonFormatByArray($arr);
    }
    /**
     * 将数组格式化为可视化的json
     *
     * @param array $arr 要格式化的数组
     *
     * @return string
     */
    protected function jsonFormatByArray(array $arr): array
    {
        $json = [];
        foreach ($arr as $key => $value) {
            $formatKey = $key;
            if (is_string($key)) {
                $formatKey = '<span class=key>\'' . $key . '\'</span>';
            }
            if (is_array($value)) {
                $formatValue = $this->jsonFormatByArray($value);
                $json[$formatKey] = $formatValue;
                continue;
            }
            $cls = 'number';
            if (is_string($value)) {
                $cls = 'string';
            }
            if (is_bool($value)) {
                $cls = 'boolean';
            }
            if (is_null($value)) {
                $cls = 'null';
            }
            $formatValue = '<span class=' . $cls . '>\'' . $value . '\'</span>';
            $json[$formatKey] = $formatValue;
        }
        return $json;
    }
}
