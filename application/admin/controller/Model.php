<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Model extends Common
{
    public function model_list()
    {
        try{
            $modeRes=\db('model')->order('id desc')->paginate(5,false);
            $prefix=config('database.prefix');
            $page = $modeRes->render();
            $this->assign(
                ['page'=>$page,
                'data'=>$modeRes,
                'prefix'=>$prefix
            ]);
            return $this->view->fetch('list');
        }catch (\think\exction\TemplateNotFoundException $e) {
            return return_exception($e);
        }
        return $this->fetch('list');
    }
    public function model_add()
    {
        if(request()->isPost()){
            $data=input('post.');
            $tableName=$data['table_name'];
            $tableName=config("database.prefix").$tableName;   //设置表名为前缀加前端获取的表名。config("database.prefix")自动读取配置文件。
            $isTable=Db::query("SHOW TABLES LIKE '{$tableName}'");
            if($isTable){
                $this->error("数据表'{$tableName}'已经存在!");
            }else{
                $sql="create table {$tableName} (aid int unsigned not null)engine= MYISAM charset=utf8";    //拼装创建数据表的sql语句；
                Db::execute($sql);
                $result=db('Model')->insert($data);
                if($result){
                    $this->success("添加模型成功!",'model_list');
                } else{
                    $this->error('添加模型失败!');
                }
            }
        return;
        }
        return $this->fetch('add');
    }
    public function changeStatus(){
        if (request()->isAjax()){
            $modelid=input('modelid');
            $status=\db('model')->field('status')->where('id',$modelid)->find();
            $status=$status['status'];
            if($status==1){
                \db('model')->where('id',$modelid)->update(['status'=>0]);
                echo 1;
            }else{
                \db('model')->where('id',$modelid)->update(['status'=>1]);
                echo 2;
            }
        }
    }
    public function model_edit(){
        if (request()->isPost()){
            $data=input('post.');
            $oldTableName=\db('model')->field('table_name')->find($data['id']);
            $oldTableName=$oldTableName['table_name'];
            $save=\db('model')->update($data);
            if( $oldTableName!=$data['table_name']){
                $prefix=config("database.prefix");
                $oldTableName=$prefix. $oldTableName;
                $tableName=$prefix.$data['table_name'];
                $sql="ALTER TABLE {$oldTableName} RENAME {$tableName}";
                Db::execute($sql);
            }
            if($save!==false){
                $this->success('修改模型成功!',url('model_list'));
            }else{
                $this->error('修改模型失败!');
            }
        }
        $id=input('id');
        $data=\db('model')->find($id);
        $this->assign('data',$data);
        return $this->view->fetch('edit');
    }
    public function model_del(){
        if (request()->isAjax()){
            $tableName=input('tableName');
            $tableName=config('database.prefix').$tableName;
            $modelId=input('modelId');
            $del=\db('model')->delete($modelId);
            $sql="drop table {$tableName}";
            $res=Db::execute($sql);
            if($del){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            $this->error("非法操作");
        }
    }
}
