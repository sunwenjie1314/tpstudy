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
        if(request()->isPost()){
            $data=input('post.');
            dump($data);
            die();
            return;
        }
        $modelRes=db('model')->field('id,model_name')->select();
        $this->assign(['modelRes'=>$modelRes]);
        return $this->view->fetch('add');
    }
    public function upImg(){
        $file=request()->file('img');
        $info=$file->move(ROOT_PATH.'public/static/admin/uploads/cateimg');
        if ($info){
            //成功上传图片后获取上传信息
            echo $info->getSaveName();
        }else{
            echo $info->getError();
        }
    }
}