<?php
/**
 * Created by PhpStorm.
 * User: 文杰
 * Date: 2018/4/8
 * Time: 14:38
 */
namespace app\admin\controller;
class Menu extends Common{
    public function menu_list(){

        return $this->view->fetch('list');
    }
    public function menu_add(){
        $data=input('post.');
        return $this->view->fetch('add');
    }
}