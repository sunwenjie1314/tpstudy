<?php
/**
 * Created by PhpStorm.
 * User: 文杰
 * Date: 2018/4/8
 * Time: 14:38
 */
namespace app\admin\controller;
use think\Db;

class ModelFields extends Common{
    public function model_fields_list(){
        $prefix=config("database.prefix");
        $data=\db('ModelFields')->field('a.*,b.model_name')->alias('a')->join("{$prefix}model b",'a.model_id=b.id')->paginate(5,false);
        $page=$data->render();
        $this->assign(['data'=>$data,
            'page'=>$page]);
       return $this->view->fetch('list');
    }
    public function model_fields_add(){
        if(request()->isPost()){
            $data=input('post.');
            //获取要被添加字段的附加表
            //$tableName=db('model')->where(['id'=>$data['model_id']])->column('table_name');
            $tableName=db('model')->field('table_name')->find($data['model_id']);
            $tableName=config("database.prefix").$tableName['table_name'];
            //将可选项中的中分逗号分割为英文。
            if($data['field_values']){
                $data['field_values']=str_replace('，',',',$data['field_values']);
            }
            //验证数据
            $valiDate=validate('model_fields');
            if (!$valiDate->scene('add')->check($data)){
                $this->error($valiDate->getError());
            }
            $result=db('ModelFields')->insert($data);
            if($result){
                //向附加表中添加相应的字段
                switch ($data['field_type']){
                    //字段类型：1：单行文本 2：单选按钮 3：复选框 4：下拉菜单 5：文本域 6：附件 7：浮点 8：整型 9：长文本类型 longtext
                    case 1:       //单选按钮、复选框、下拉菜单存储的都是器选项值，用逗号分开即可，附件类型存储的是附件的地址，用字符串即可
                    case 2:
                    case 3:
                    case 4:
                    case 6:
                    $fileType='varchar(150) not null default ""';
                        break;
                    case 5:
                        $fileType='varchar(150) not null default ""';
                        break;
                    case 7:
                        $fileType='float not null default "0.0"';
                        break;
                    case 8:
                        $fileType='int not null default "0"';
                        break;
                    case 9:
                        $fileType="longtext";
                        break;
                    default:
                        $fileType='varchar(150) not null default ""';
                        break;
                }
                $sql="alter table {$tableName} add {$data["field_ename"]} {$fileType}";
                Db::execute($sql);
                $this->success('添加字段成功!','model_fields_list');
            } else{
                $this->error('添加字段失败!');
            }
            return;
        }
        $models=db('model')->field('id,model_name')->select();
        $this->assign('modelRes',$models);
        return $this->view->fetch('add');
    }
    //编辑字段信息
    public function model_fields_edit(){
        if (request()->isPost()){
            $data=input('post.');
            $prefix=config('database.prefix');
            $modelName=$prefix.'model';
            //联表查询所需的表名和字段名
            $fieldInfo=\db('ModelFields')->field('a.field_ename,b.table_name')->alias('a')->join("$modelName b",'a.model_id=b.id')->find($data['id']);
           // $tableName=\db('model')->field('table_name')->where(['id'=>$data['model_id']])->find(); //获取要字段对应的表名
            $tableName=config('database.prefix').$fieldInfo['table_name'];  //需要修改的附加表表名
            $oldFieldName=$fieldInfo['field_ename'];    //获取原始的字段名

            //将可选项中的中分逗号分割为英文。
            if($data['field_values']){
                $data['field_values']=str_replace('，',',',$data['field_values']);
            }
            $valiDate=validate('ModelFields');
            if (!$valiDate->scene('edit')->check($data)){
                $this->error($valiDate->getError());
            }
            $update=\db('model_fields')->update($data); //修改字段表
            //向附加表中添加相应的字段
            if($update){
                switch ($data['field_type']){
                    //字段类型：1：单行文本 2：单选按钮 3：复选框 4：下拉菜单 5：文本域 6：附件 7：浮点 8：整型 9：长文本类型 longtext
                    case 1:       //单选按钮、复选框、下拉菜单存储的都是器选项值，用逗号分开即可，附件类型存储的是附件的地址，用字符串即可
                    case 2:
                    case 3:
                    case 4:
                    case 6:
                        $fileType="varchar(150) not null default ''";
                        break;
                    case 5:
                        $fileType="varchar(150) not null default ''";
                        break;
                    case 7:
                        $fileType="float not null default '0.0'";
                        break;
                    case 8:
                        $fileType="int not null default '0'";
                        break;
                    case 9:
                        $fileType="longtext";
                        break;
                    default:
                        $fileType="varchar(150) not null default ''";
                        break;
                }
                $sql="alter table {$tableName} change {$oldFieldName} {$data['field_ename']} {$fileType}";
                Db::execute($sql);
                $this->success("修改字段成功!",'model_fields_list');
            }else{
                $this->error('修改字段失败');
            }
            return;
        }
        $id=input('id');
        $fields=\db('ModelFields')->where(['id'=>$id])->find();
        $models=db('model')->field('id,model_name')->select();
        $this->assign(
            [
                'fields' =>$fields,
                'modelRes'=>$models,
             ]);
        return $this->view->fetch('edit');
    }
    //模型删除
    public function model_fields_del(){
        if (request()->isAjax()){
            $id=input('id');   //获取字段的id；
            $modelId=input('modelId');   //获取表的id
            $tableName=\db('model')->field('table_name')->where(['id'=>$modelId])->find(); //获取要字段对应的表名
            $fieldsName=\db('ModelFields')->field('field_ename')->find($id);   //获取需要删除的字段的名称
            $fieldsName=$fieldsName['field_ename'];
            $tableName=config('database.prefix').$tableName['table_name'];
            $sql="alter table {$tableName} drop column $fieldsName";
            Db::execute($sql);
            $del=\db('modelFields')->delete($id);
            if($del){
                echo 1;
            }else{
                echo 2;
            }
        }
    }
}