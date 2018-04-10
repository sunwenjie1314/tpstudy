<?php
/**
 * Created by PhpStorm.
 * User: 文杰
 * Date: 2018/3/28
 * Time: 10:18
 */
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Loader;
use think\Request;

class Conf extends Common{
    public function config_list(){

        $list=Db::table('tp_conf')->paginate(5);
        $this->assign('list',$list);
        return $this->fetch('list');
    }
    public function config_add(){
        if (request()->isPost()){
            $data=input('post.');
            $validate=Loader::validate('Conf');
            if (!$validate->scene('add')->check($data)){               //执行格式验证
                $this->error($validate->getError());    //显示错误验证信息
            }
            $add=Db::table('tp_conf')->insert($data);
            if ($add){
                $this->success('添加配置成功!',url('config_list'));
            }else{
                $this->error('添加数据失败!');
            }
        }
        return $this->fetch('add');
    }
    public function config_del(Request $request){
        if (request()->isAjax()){
            $id=$request->param('id');
            $data=Db::table('tp_conf')->delete($id);
            if($data){
                $status=1;
                $message="删除配置项成功!";
            }else{
                $status=1;
                $message="删除配置项失败!";
            }
            return ['status'=>$status,'message'=>$message];
        }
    }
    public function config_edit($id){
        if (request()->isPost()){
            $data=input('post.');
            $validate=Loader::validate('Conf');
            if (!$validate->check($data)){               //执行格式验证
                $this->error($validate->getError());    //显示错误验证信息
            }
            $save=Db::table('tp_conf')->update($data);
            if ($save){
                $this->success('更新配置成功!',url('config_list'));
            }else{
                $this->error('更新配置失败!');
            }
        }
        $confs=Db::table('tp_conf')->find($id);
        $this->assign('confs',$confs);
        return $this->view->fetch('edit');
    }

    public function config_configs(){
        if(\request()->isPost()){
            $data=input('post.');
            $enameArr=Db::table('tp_conf')->column('ename');     //读取数据库中的字段列表
            $confArr=array();     //定义数据confArr用来存储前端发送过来的字段
            $filecolumn=\db('conf')->where('dt_type',6)->column('ename');  //取出附件类型为文件的字段
            foreach($data as $k=>$v){
                $confArr[]=$k;    //讲前端发送过来的字段存入数组
                if(is_array($v)){
                    $v=implode(',',$v);
                }
                Db::table('tp_conf')->where('ename',$k)->update(['value'=>$v]);
            }
            foreach ($enameArr as $k=>$v){       //遍历数据库中的字段，如果数据库中的字段不在前端发送过来的字段中，那么，就把该字段的值，更改为空。
                if(!in_array($v,$confArr) && !in_array($v,$filecolumn)){
                    Db::table('tp_conf')->where('ename',$v)->update(['value'=>'']);
                }
            }
            //附件类型文件处理
            foreach($filecolumn as $k=>$v){
                //判断是否有上传图片,如果没有添加图片，那么就不处理他
                if($_FILES[$v]['tmp_name'] != ''){
                    $file=request()->file($v);
                    $info=$file->move(ROOT_PATH.'public/static/admin/uploads');  //将图片存储到该目录下
                    $fileSrc=$info->getSaveName();       //获取存储后的图片的名称
                    if($fileSrc!=''){
                        db('conf')->where('ename',$v)->update(['value'=>$fileSrc]);}
                }
            }
            $this->success('更新数据成功!','config_configs');
            return;
        }

        $confres=Db::table('tp_conf')->select();
        $this->assign('confres',$confres);
        return  $this->view->fetch('cflst');
    }
}