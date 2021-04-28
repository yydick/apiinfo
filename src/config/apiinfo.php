<?php

//ApiInfo配置文件

return [
    'default' => [
        /**
         * 要扫描的路由前缀
         */
        'prefix' => ['example/apiinfo'],
        /**
         * 要扫描的路由命名空间, 暂时保留
         */
        'namespace' => '',
        'cachePath' => storage_path(),
        'cacheTimeOut' => 0,
        ''
    ],
];
