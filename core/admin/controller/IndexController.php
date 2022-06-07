<?php

namespace core\admin\controller;

use core\base\controller\BaseController;
use core\admin\model\Model;


class IndexController extends BaseController
{
    protected function inputData()
    {
        $db = Model::instance();

        $table = 'articles';

        $files['img'] = 'main_img.jpg';
        $files['gallery_img'] = ["red'' .jpg", 'blue.jpg', 'black.jpg'];


        $res = $db->showColumns($table);

        exit('id =' . $res['id'] . ' Name = ' . $res['name']);
    }
}