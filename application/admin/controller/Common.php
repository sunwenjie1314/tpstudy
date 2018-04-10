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

class Common extends Controller{
    public function _initialize()
    {
        parent::_initialize(); //
        $request=Request::instance();
        $conf=$request->controller();    //获取当前控制器的名称
       // $action=$request->action();    //获取当前方法的名称
        $this->assign('conf',$conf);
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
}