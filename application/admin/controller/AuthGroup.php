<?php
/**
 * Created by PhpStorm.
 * User: 文杰
 * Date: 2018/4/8
 * Time: 14:38
 */
namespace app\admin\controller;
use app\admin\model\AuthGroup as AuthGroupModel;
class AuthGroup extends Common{
    public function auth_group_list(){
        try{
            $authGroup=new AuthGroupModel();
            $roleres=$authGroup->paginate(5,false);
            $page = $roleres->render();
            $this->assign('page',$page);
            $this->assign('roleres',$roleres);
            return $this->view->fetch('list');
        }catch (\think\exction\TemplateNotFoundException $e){
            return return_exception($e);
    }

    }
    public function auth_group_add(){
        if(request()->isPost()){
            try{
                $param=input('post.');
                $result=db('auth_group')->field('title,status,rules')->insert($param);
                if($result){
                    $this->success('添加角色成功!','auth_group_list');
                }else{
                    $this->error('添加角色失败!');
                }
            }catch (\think\exception\TemplateNotFoundException $e){    //如果出现异常就抛出
                return return_exception($e);
        }
        }
        return $this->view->fetch('add');
    }
}