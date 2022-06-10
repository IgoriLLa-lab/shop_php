<?php

namespace core\admin\controller;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use core\base\settings\ShopSettings;

class ShowController extends BaseAdmin
{

    protected function inputData()
    {
        $this->execBase();


        $this->createTableData();


        $this->createData();


//        return $this->expansion(get_defined_vars());

    }

    //отрисовка
    protected function outputData()
    {
//        $args = func_get_args(0);
//        $vars = $args ? $args : [];
//
//        if (!$this->template) $this->template = ADMIN_TEMPLATE . 'show';
//
//        $this->content = $this->render($this->template, $vars);
//
//        return parent::outputData();

    }


}