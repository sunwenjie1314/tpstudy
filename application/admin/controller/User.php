<?php
/**
 * Created by PhpStorm.
 * User: 文杰
 * Date: 2018/4/10
 * Time: 10:24
 */
namespace app\admin\controller;
use think\Db;
use think\exception\TemplateNotFoundException;

class User extends Common{

    public function user_list(){
        //$list=db('user')->select();
        //使用多表联合查询。同时获取用户组的信息。
        $list=Db::table('tp_user')->alias('a')->join('tp_auth_group b','a.groupid=b.id')->field(
            'a.id,a.name,a.nick_name,a.status,a.create_time,a.last_time,b.title')->select();    //通过添加field字段属性来规避联合查询可能导致的id显示错误。
        foreach ($list as $k=>$v){
            if($v['status']===1){
                $list[$k]['status']="启用";
            }
            if($v['status']===0){
                $list[$k]['status']='禁用';
            }
            $list[$k]['last_time']=date("Y-m-d H:i:s",$v['last_time']);
            $list[$k]['create_time']=date("Y-m-d H:i:s",$v['create_time']);
        }
        $this->assign('list',$list);
        return $this->view->fetch('list');
    }
    public function user_add(){
        //获取已经启用的角色信息
        $auth_group=db('auth_group')->where(['status'=>1])->select();
        $this->assign('authGroup',$auth_group);

        //添加用户信息
        try{
            if (request()->isPost()){
                $data=input('post.');
                $data=array(
                    'name'=>$data['name'],
                    'nick_name'=>$data['nick_name'],
                    'password'=>md5($data['password']),
                    'status'=>$data['status'],
                    'create_time'=>time(),
                    'last_time'=>0,
                    'groupid'=>$data['auth_group']
                );
                $result=db('user')->insert($data);
                if ($result){
                    $this->success('添加用户成功!','user_list');
                }else{
                    $this->error('添加用户失败!');
                }}
        }catch(TemplateNotFoundException $e){
            return exception($e);
        }
        return $this->view->fetch('add');
    }
}