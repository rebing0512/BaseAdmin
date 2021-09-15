<?php
namespace Jenson\BaseAdmin\Controllers;

use Illuminate\Http\Request;

use Jenson\BaseAdmin\Libraries\Helper;
use Jenson\BaseAdmin\Models\Admin;
use Carbon\Carbon;

Class LoginController extends BaseController
{
    public function login(Request $request)
    {
        if (session('is_login')) {
            return redirect()->route('admin.default');
        }
        return view('mbcore.baseadmin::login.login');
    }

    public function auth(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => '用户名不能为空',
            'password.required' => '密码不能为空'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->get('username')){
            //用户名登录
            $name = $request->get('username');
            $user = Admin::where('username',$name)->first();
        }

        // :todo 记录登录状态，暂不考虑
        /*
        $request->remember == 'on' ? $remember = true : $remember = false;
        if($remember == true){     //如果用户选择了，记录登录状态就把用户名和加了密的密码放到cookie里面
            setcookie("username", $name, time()+3600*24*30);
            setcookie("password", $request->get('password'), time()+3600*24*30);
        }
        //*/

        if (!$user){
            return redirect()->back()->withErrors(["用户名不存在"])->withInput();
        }else{
            $password = $request->get('password');
            if (password_verify($password,$user->password)){
                //获取用户登录时间,IP储存
                $loginTime = Carbon::now();
                $loginIP = $_SERVER['REMOTE_ADDR'];

                //将用户登录时间,IP储存
                Admin::query()->where('id',$user->id)->update([
                    'last_login_time' => $loginTime,
                    'last_login_ip' => $loginIP,
                ]);
                //将用户信息闪存入session
                session(['uid' => $user->id]);
                session(['username' => $user->username]);
                session(['is_login' => true]);

                //设置权限信息
                $this->setUserRolesSession($request,$user->roles);

                // 记录登录日志
                if(config('mbcore_baseadmin.baseadmin_admin_log_add')) {
                    $params = [
                        'admin_id' => $user['id'],
                        'operation' => '登录',
                        'ip' => $loginIP,
                    ];
                    Helper::addAdminLog($params);
                }
                return redirect()->route('admin.default');
            }else{
                return redirect()->back()->withErrors(["用户名或密码错误"])->withInput();
            }
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * 退出登录
     */
    public function logout(Request $request)
    {
        if ($request->session()->has('is_login')) {
            $is_forgotten = session()->flush(); //删除所有session数据
            if ($is_forgotten === null)
                return redirect()->route('login.login');
        }
        return redirect()->back()->withErrors('系统异常，退出失败,请重试');
    }

}