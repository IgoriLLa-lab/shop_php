<?php

namespace core\base\settings;

use core\base\settings\Settings;

class ShopSettings
{

    //singleton pattern
    static private $_instance;
    private $baseSettings;

    private $routes = [
        'plugins' => [
            'path' => 'core/plugins/',
            'hrUrl' => false,
            'dir' => 'controller'
        ],
    ];

    private $templateArr = [
        'text' => ['price', 'short'],
        'textarea' => ['goods_content']
    ];

    static public function get($property)
    {
        return self::getInstance()->$property;

    }

    //singleton pattern
    static public function getInstance()
    {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }
        self::$_instance = new self();
        self::$_instance->baseSettings = Settings::getInstance();
        $baseProperties = self::$_instance->baseSettings->clueProperties(get_class());
        self::$_instance->setProperties($baseProperties);

        return self::$_instance;
    }

    protected function setProperties($properties)
    {
        if ($properties) {
            foreach ($properties as $name => $property) {
                $this->$name = $property;
            }
        }
    }

    //singleton pattern
    private function __construct()
    {
    }

    //singleton pattern
    private function __clone()
    {
    }
}