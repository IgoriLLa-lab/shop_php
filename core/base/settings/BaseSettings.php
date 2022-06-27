<?php

namespace core\base\settings;

use core\base\controller\Singleton;
use core\base\exceptions\DbException;
use core\base\settings\Settings;

trait BaseSettings
{

    use Singleton {
        instance as singleToneInstance;
    }

    private $baseSettings;

//    private string $expansion = 'core/plugin/expansion/';

    /**
     * @throws DbException
     */
    static public function get($property)
    {
        return self::instance()->$property;

    }

    //singleton pattern in Trait Singleton
    static private function instance()
    {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }

        self::singleToneInstance()->baseSettings = Settings::instance();
        $baseProperties = self::$_instance->baseSettings->clueProperties(get_class());
        self::$_instance->setProperties($baseProperties);


        return self::$_instance;

    }

    protected function setProperties($properties): void
    {
        if ($properties) {
            foreach ($properties as $name => $property) {
                $this->$name = $property;
            }
        }
    }
}