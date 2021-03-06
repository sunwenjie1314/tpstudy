<?php
/**
 * Created by PhpStorm.
 * User: 文杰
 * Date: 2018/4/10
 * Time: 11:21
 */
namespace app\admin\model;

use think\Model;

class Menu extends Model{
    public function menutree($menus){
        return $this->tree($menus);
    }
    public function tree($arr,$pid=0,$level=0){
        static $tree = array();
        foreach($arr as $v){
            if($v['parent_id']==$pid){
                //说明找到，保存
                $v['level'] = $level;
                $tree[] = $v;
                //继续找
                $this-> tree($arr,$v['id'],$level+1);
            }
        }
        return $tree;
    }
}