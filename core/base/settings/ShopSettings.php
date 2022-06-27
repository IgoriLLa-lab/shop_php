<?php

namespace core\base\settings;

use core\base\controller\Singleton;
use core\base\exceptions\DbException;
use core\base\settings\Settings;

class ShopSettings
{

    use BaseSettings;

    private array $routes = [
        'plugins' => [
            'dir' => false,
            'routes' => [
                'route1' => ['1', '2']
            ]
        ],
    ];

    private array $templateArr = [
        'text' => ['price', 'short', 'name'],
        'textarea' => ['goods_content']
    ];


}