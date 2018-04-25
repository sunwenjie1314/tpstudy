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

class Model extends Validate{
    protected  $rule=[
        'model_name'=>'require|max:60|unique:model',   //验证选择项。
        'table_name'=>'require|unique:model',
    ];
    protected $message=[
        'model_name.require'=>'中文名称不得为空!',
        'model_name.max'=>'中文名称最大长度不得超过60个字符!',
        'model_name.unique'=>'模型名称不得重复!',
        'table_name.require'=>'附加表名称不得为空!',
        'table_name.unique'=>'附加表名称不得重复!',
    ];

    protected $scene=[        //添加场景验证
        'add'=>['model_name','table_name'],    //新增时仍保持原来的场景不变
        'edit'=>['model_name','table_name'],    //编辑时仍保持原来的场景不变
    ];
}