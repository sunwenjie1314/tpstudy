<?php
/**
 * Created by PhpStorm.
 * User: 文杰
 * Date: 2018/4/8
 * Time: 13:44
 */
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;

class Common extends Controller{
    public function _initialize()
    {
        parent::_initialize(); //
        $request=Request::instance();
        $conf=$request->controller();    //获取当前控制器的名称

       // $action=$request->action();    //获取当前方法的名称
        $this->assign('conf',$conf);

        define('USER_ID', Session::get('user_id'));       //静态的定义“USER_ID”这个常量，用于后续的判定操作。USER_ID就是SESSION中的用户id。
    }
    public function positon($cid){
        static $pos = array();
        if (empty($pos)) {
            $cates = db('cate')->field('id,cate_name,pid,cate_attr')->find($cid);
            $pos[] = $cates;
        }
        $data = db('cate')->field('id,cate_name,pid,cate_attr')->select(); //所有栏目信息
        $cates=db('cate')->field('id,cate_name,pid,cate_attr')->find($cid);//当前栏目信息
        foreach ($data as $k=>$v){
            if ($cates['pid']==$v['id']){
                $pos[]=$v;
                $this->positon($v['id']);
            }
        }
        return array_reverse($pos);
    }
    //判断用户是否登录，放在后台的入口文件中：index/index    即index控制的index方法中
//    protected function isLogin()
//    {
//        if (empty(USER_ID)) {
//            $this->error('用户未登录，无权访问!', url('Login/login'));
//        }
//    }
//    //防止用户重复登录
//    protected function alreadyLogin()
//    {
//        if (!empty(USER_ID)) {
//            $this->error('用户已经登录,请勿重复登录!', url('index/index'));
//        }
//    }
}