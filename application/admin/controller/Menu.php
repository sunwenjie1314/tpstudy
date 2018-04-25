<?php
/**
 * Created by PhpStorm.
 * User: 文杰
 * Date: 2018/4/8
 * Time: 14:38
 */
namespace app\admin\controller;
//use app\admin\model\Menu as MenuModel;
class Menu extends Common{
    public function menu_list(){
        $list=db('menu')->select();
        foreach ($list as $k=>$v){
            if($v['status']===1){
                $list[$k]['status']="启用";
            }
            if($v['status']===0){
                $list[$k]['status']='禁用';
            }
        }
        $this->assign('list',$list);
        return $this->view->fetch('list');
    }
    public function menu_add(){
        $_menus=db('menu')->select();
        $menus=model('menu')->menutree($_menus);
        $this->assign('menus',$menus);
        if(request()->isPost()){
            $data=input('post.');
            $result=db('menu')->insert($data);
            if ($result){
                $this->success('添加菜单成功!','menu_list');
            }
            else{
                $this->error('添加菜单失败!');
            }

        }

        return $this->view->fetch('add');
    }
}