<?php
/**
 * Created by PhpStorm.
 * User: 文杰
 * Date: 2018/4/11
 * Time: 14:58
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Login extends Controller{
public function index(){
    return $this->view->fetch();
}
public function checkLogin(Request $request){
    $status = 0;
    $result = '用户名或密码错误!';
    $data = $request->param();
    // 创建验证规则
    $rule = [
        'name|用户名' => 'require',
        'password|登录密码' => 'require',
        'verify|验证码' => 'require|captcha'
    ];
    // 创建自定义提示
    $msg = [
        'name' => [
            'require' => '用户名不能为空,请检查!'
        ],
        'password' => [
            'require' => '用户密码为空,请检查!'
        ],
        'verify' => [
            'require' => '验证码不能为空,请检查!',
            'captcha' => '验证码错误'
        ]
    ];

    // 进行验证
    $result = $this->validate($data, $rule);

    // 如果验证通过则执行
    if ($result === true) {
        // 构造查询条件
        $map = [
            'name' => $data['name'], // 用户名等于从前台获取的用户名
            'password' => md5($data['password']) // 对从前端获取的用户密码进行加密
        ];
        // 查询用户信息
        $user =\app\admin\model\User::get($map);
        if ($user === null) {
            $result = '用户名或密码错误!';
        }elseif ($user['status']===0){
            $status = 2;
            $result="该账号已经被禁用!";
        }
        else {
            $status = 1;
            $result = '登录成功，正在跳转……';
            // 设置使用记录用户登录信息session
            Session::set('user_id', $user->id);
          //  Session::set('user_nickname',$user->nick_name);
            Session::set('user_info', $user->getData()); // 设置获取用户所有登录信息

            // 统计用户登录次数
            //$user->setInc('login_count');
        }
    }
    return [
        'status' => $status,
        'message' => $result,
        'data' => $data
    ];
}
    public function logout()
    {
        // 推出前更新登录时间，这样下次登录时就知道上次登录的时间
        \app\admin\model\User::update([
            'last_time' => time()
        ], [
            'id' => Session::get('user_id')
        ]);
        Session::delete('user_id'); // 删除用户的id信息
        Session::delete('user_info'); // 删除用户的全部信息
        $this->success('注销登录,正在返回', url('Login/index'));
    }
    public function test(){
        $map = [
            'name' => "admin", // 用户名等于从前台获取的用户名
            'password' => md5(123456) // 对从前端获取的用户密码进行加密
        ];
        // 查询用户信息
       // $user =db('user')->where($map)->select();
        $user=\app\admin\model\User::get($map);
        dump($user);
        die();
    }
}