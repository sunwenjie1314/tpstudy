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

class Conf extends Validate{
    protected  $rule=[
        'cname'=>'require|max:60|unique:conf',   //验证选择项。
        'ename'=>'require|max:70|unique:conf',
        'dt_type'=>'require|number',
        'cf_type'=>'require|number',
    ];
    protected $message=[
        'cname.require'=>'中文名称不得为空!',
        'cname.max'=>'中文名称最大长度不得超过60个字符!',
        'cname.unique'=>'中文名称不得重复!',
    ];

    protected $scene=[        //添加场景验证
        'add'=>['cname','ename'],    //新增时仍保持原来的场景不变
    ];
}