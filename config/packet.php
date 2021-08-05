<?php

return [
    /**
     * 应用配置
     */
    'api' => [  // API
        'namespace' => 'App\Web\Api\Controllers',
        'guard' => 'api',
    ],
    'backend' => [  // 后台
        'namespace' => 'App\Web\Backend\Controllers',
        'viewPath' => 'views/backend',
        'guard' => 'backend',
    ],
    'frontend' => [ // 前台
        'namespace' => 'App\Web\Frontend\Controllers',
        'viewPath' => 'views/frontend',
        'guard' => 'frontend',
    ],
];
