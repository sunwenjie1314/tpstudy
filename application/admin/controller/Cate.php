<?php
/**
 * Created by PhpStorm.
 * User: 文杰
 * Date: 2018/4/8
 * Time: 14:38
 */
namespace app\admin\controller;
class Cate extends Common{
    public function cate_list(){
        return $this->view->fetch('list');
    }
    public function cate_add(){
        return $this->view->fetch('add');
    }
}