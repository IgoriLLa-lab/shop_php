<?php

namespace core\base\controller;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;

//подключение методов
abstract class BaseController
{

    use \core\base\controller\BaseMethods;
    protected $page;
    protected $errors;

    protected $controller; //
    protected $inputMethod; // св-во в тором будет храниться метод собирающий данные из БД
    protected $outputMethod; // в нем будет храниться метод который будет отвечать за подключение видов
    protected $parameters; // параметр

    protected $styles;
    protected $scrypts;


    /**
     *
     * @throws RouteException
     */
    public function route()
    {

        $controller = str_replace('/', '\\', $this->controller);

        try {
            //reflection
            $object = new \ReflectionMethod($controller, 'request');

            $args = [
                'parameters' => $this->parameters,
                'inputMethod' => $this->inputMethod,
                'outputMethod' => $this->outputMethod
            ];

            $object->invoke(new $controller, $args);

        } catch (\ReflectionException $e) {
            throw new RouteException($e->getMessage());
        }
    }

    public function request($args)
    {
        $this->parameters = $args['parameters'];

        $inputData = $args['inputMethod'];
        $outputData = $args['outputMethod'];

        $data = $this->$inputData();

        if (method_exists($this, $outputData)) {
            $page = $this->$outputData($data);
            if ($page) $this->page = $page;
        } else if ($data) {
            $this->page = $data;
        }

        if ($this->errors) {
            $this->writeLog($this->errors);
        }

        $this->getPage();
    }

    //шаблонизатор
    protected function render($path = '', $parameters = [])
    {
        extract($parameters);

        if (!$path) {

            $class = new \ReflectionClass($this);

            $space = str_replace('\\', '/', $class->getNamespaceName() . '\\');
            $routes = Settings::get('routes');

            if ($space === $routes['user']['path']) $template = TEMPLATES;
            else $template = ADMIN_TEMPLATE;

            $path = $template . explode('controller', strtolower($class->getShortName()))[0];
        }

        ob_start();

        if (!@include_once $path . '.php') throw new RouteException('Отсутствует шаблон - ' . $path);

        return ob_get_clean();

    }

    protected function getPage()
    {
        if (is_array($this->page)) {
            foreach ($this->page as $block) echo $block;
        } else {
            echo $this->page;
        }
        exit();
    }

    /**
     * @return void
     * Инициализирует стили и скрипты ADMIN_CSS_JS и USER_CSS_JS
     */
    protected function init($admin = false)
    {
        if (!$admin) {
            if (USER_CSS_JS['styles']) {
                foreach (USER_CSS_JS['styles'] as $item) $this->styles[] = PATH . TEMPLATES . trim($item, '/');
            }
            if (USER_CSS_JS['scripts']) {
                foreach (USER_CSS_JS['scripts'] as $item) $this->scrypts[] = PATH . TEMPLATES . trim($item, '/');
            }
        } else {
            if (ADMIN_CSS_JS['styles']) {
                foreach (USER_CSS_JS['styles'] as $item) $this->styles[] = PATH . ADMIN_TEMPLATE . trim($item, '/');
            }
            if (ADMIN_CSS_JS['scripts']) {
                foreach (USER_CSS_JS['scripts'] as $item) $this->scrypts[] = PATH . ADMIN_TEMPLATE . trim($item, '/');
            }
        }
    }
}