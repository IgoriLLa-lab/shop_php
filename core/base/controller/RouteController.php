<?php

namespace core\base\controller;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;

//через этот класс идет вся система маршрутов (точка входа системы контроллеров)
class RouteController extends BaseController
{
    use Singleton;

    protected $routes; // св-во маршрутов из settings

    /**
     * @throws RouteException
     */
    private function __construct()
    {
        $address_str = $_SERVER['REQUEST_URI']; //1. получаем адресную строку из глобального массива Сервер

        if ($_SERVER['QUERY_STRING']) {
            $address_str = substr($address_str, 0, strrpos($address_str, $_SERVER['QUERY_STRING']) - 1);
        }


        $path = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], 'index.php'));

        if ($path === PATH) {

            if (strrpos($address_str, '/') === strlen($address_str) - 1 &&
                strrpos($address_str, '/') !== strlen(PATH) - 1) {

                $this->redirect(rtrim($address_str, '/', 301, null));
            }

            $this->routes = Settings::get('routes');

            if (!$this->routes) throw new RouteException('Отсутствуют маршруты в Базовых настройках', 1);

            $url = explode('/', substr($address_str, strlen(PATH)));

            if ($url[0] && $url[0] === $this->routes['admin']['alias']) {

                array_shift($url);

                if ($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])) {

                    $plugin = array_shift($url);

                    $pluginSettings = $this->routes['settings']['path'] . ucfirst($plugin . 'Settings');

                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . '.php')) {
                        $pluginSettings = str_replace('/', '\\', $pluginSettings);
                        $this->routes = $pluginSettings::get('routes');
                    }

                    $dir = $this->routes['plugins']['dir'] ? '/' . $this->routes['plugins']['dir'] . '/' : '/';
                    $dir = str_replace('//', '/', $dir);

                    $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;

                    $hrUrl = $this->routes['plugins']['hrUrl'];

                    $route = 'plugins';

                } else {
                    $this->controller = $this->routes['admin']['path'];

                    $hrUrl = $this->routes['admin']['hrUrl'];

                    $route = 'admin';
                }

            } else {

                $hrUrl = $this->routes['user']['hrUrl'];

                $this->controller = $this->routes['user']['path'];

                $route = 'user';
            }

            $this->createRoute($route, $url);

            if (isset($url[1])) {
                $count = count($url);
                $key = '';

                if (!$hrUrl) {
                    $i = 1;
                } else {
                    $this->parameters['alias'] = $url[1];
                    $i = 2;
                }

                for (; $i < $count; $i++) {
                    if (!$key) {
                        $key = $url[$i];
                        $this->parameters[$key] = '';
                    } else {
                        $this->parameters[$key] = $url[$i];
                        $key = '';
                    }
                }
            }

        } else {
            throw new RouteException('Не корректная директория сайта', 1);
        }
    }

    private function createRoute($var, $arr)
    {
        $route = [];

        if (!empty($arr[0])) {
            if (isset($this->routes[$var]['routes'][$arr[0]])) {
                $route = explode('/', $this->routes[$var]['routes'][$arr[0]]);

                $this->controller .= ucfirst($route[0] . 'Controller');
            } else {
                $this->controller .= ucfirst($arr[0] . 'Controller');
            }
        } else {
            $this->controller .= $this->routes['default']['controller'];
        }

        $this->inputMethod = isset($route[1]) ?: $this->routes['default']['inputMethod'];
        $this->outputMethod = isset($route[2]) ?: $this->routes['default']['outputMethod'];

        return;
    }
}