<?php

namespace core\admin\controller;

use core\base\controller\BaseController;
use core\admin\model\Model;
use core\base\settings\Settings;

class IndexController extends BaseController
{
    protected function inputData()
    {

        $db = Model::instance();

        $redirect = PATH. Settings::get('routes')['admin']['alias'] . '/show';
        $this->redirect($redirect);
    }
}