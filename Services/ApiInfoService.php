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
// use Illuminate\Support\Facades\Route;
// use Illuminate\Routing\Route;
use Route;

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
     * @return array
     */
    public function scanRoutes(): array
    {
        /**
         * @var \Illuminate\Routing\RouteCollection
         */
        $baseRoutes = Route::getRoutes();
        $routes = $baseRoutes->getRoutes();
        $routess = [];

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
            $configPrefix = config("apiinfo.prefix");
            if ($configPrefix) {
                // var_dump($prefix, $configPrefix);
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
        // var_dump($routes->getRoutes());
        return $routess;
    }
    /**
     * 扫描控制器生产文档
     * 
     * @param string $className 控制器对象
     * 
     * @return array
     */
    public function scanDocument(string $className)
    {
        $rc = new ReflectionClass($className);
        $doc = $rc->getDocComment();
        $methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
        echo "<br/>";
        foreach ($methods as $method) {
            $methodDoc = $method->getDocComment();
            echo "{$methodDoc}<br />";
        }
        // var_dump($doc);
        return $doc;
    }
}
