<?php
/**
 * Created by PhpStorm.
 * User: 文杰
 * Date: 2018/4/10
 * Time: 11:21
 */
namespace app\admin\model;

use think\Model;

class AuthGroup extends Model{
    public function getStatusAttr($value)
    {
        $status = [
            0 => '禁用',
            1 => '启用'
        ];
        return $status[$value];
    }
}