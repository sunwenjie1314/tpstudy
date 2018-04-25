<?php
// +----------------------------------------------------------------------
// | 配置表验证
// +----------------------------------------------------------------------
// | Copyright (common) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace app\admin\validate;

use think\Validate;

class ModelFields extends Validate{
    protected  $rule=[
        'model_id'=>'require',
        'field_cname'=>'require|chs|max:60|unique:model_fields',
        'field_ename'=>'require|alphaS|max:60|unique:model_fields',
        'field_type'=>'require',
    ];
    protected $message=[
        'model_id.require'=>'所属模型不得为空',
        'field_cname.require'=>'字段中文名称不得为空!',
        'field_cname.chs'=>'字段中文名称只能为汉字!',
        'field_cname.max'=>'中文名称长度不得大于60',
        'field_cname.unique'=>'字段中文名称不得重复',
        'field_ename.require'=>'字段英文名称不得为空!',
        'field_ename.alphaS'=>'字段英文名必须以字母开头',
        'field_ename.max'=>'英文名称长度不得大于60',
        'field_ename.unique'=>'英文名称不得重复',
        'field_type'=>'字段类型必须选择'
    ];

    protected $scene=[        //添加场景验证
        'add'=>['model_id','field_cname','field_ename','field_type'],    //新增时仍保持原来的场景不变
        'edit'=>['field_cname','field_ename','field_type'],    //编辑时仍保持原来的场景不变
    ];
}