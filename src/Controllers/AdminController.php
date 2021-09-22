<?php

namespace Jenson\BaseAdmin\Controllers;

use Illuminate\Http\Request;

use Jenson\BaseAdmin\Models\Menu;
use Jenson\BaseAdmin\Models\Admin;
use Jenson\BaseAdmin\Libraries\Helper;

class AdminController extends JensonBaseAdminController
{
    //demo
    public function rolestest(Request $request){
        if(Helper::hasVisterRoles($request)){
            dd("有权访问！");
        }else{
            dd("无权访问！");
        }
    }

    public function index(Request $request)
    {
        // test
        //echo Helper::getUserHome($request);

        $status = "home";

        //初始化菜单

        //顶级菜单
        $menus = Menu::where('parent_id',0)->orderBy('sort', 'desc')->get(["id","name","link","sort","parent_id","group_id","i_ico_class"])->toArray();
        //临时菜单数组
        $tempMenus = [];
        //重新整理数据
        foreach ($menus as $menu){
            $tempMenus[$menu["id"]] = $menu;
            $tempMenus[$menu["id"]]['hasChild'] = 0;
            $tempMenus[$menu["id"]]['isFather'] = 1;
        }
        //子菜单数据
        $subMenus = Menu::where('parent_id','<>',0)->orderBy('sort', 'desc')->get(["id","name","link","sort","parent_id"])->toArray();
        foreach ($subMenus as $menu){
            $tempMenus[$menu["parent_id"]]['nodes'][] = $menu;
            $tempMenus[$menu["parent_id"]]['hasChild'] = 1;
        }

        if(config('mbcore_baseadmin.baseadmin_menuGroup')){
            $echoMenus = [];
            foreach($tempMenus as $menu){
                $echoMenus[$menu["group_id"]][] = $menu;
            }
            //dd($echoMenus);
        }else{
            $echoMenus = $tempMenus;
        }


        //读取数据库的权限信息
        $this->setRolesArr($request);

        return view('mbcore.baseadmin::admin.index',['status' => $status,'menus'=>$echoMenus]);
    }

    public function home()
    {
        $adminHome = 'mbcore.baseadmin::admin.home';
        if(!empty(config('mbcore_baseadmin.baseadmin_homeView'))){
            $adminHome = config('mbcore_baseadmin.baseadmin_homeView');
            //dd($adminHome);
        }
        return view($adminHome);
    }

    public function add(Request $request){
        $back_url = $request->get('back_url',null);
        //读取数据库的权限信息
        //$this->setRolesArr($request);
        return view('mbcore.baseadmin::admin.add',[
            'back_url'=>$back_url,
            'group_id'=>$request->get('group_id')
        ]);
    }

    public function addsave(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required',
            'fullName' => 'required',
            'email' => 'required|email',
            'status' => 'numeric',
        ], [
            'username.required' => '用户名不能为空',
            'password.required' => '密码不能为空',
            'fullName.required' => '姓名不能为空',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确',
            'status.numeric' => '状态字段异常',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        //dd("test");

        // 判断用户名或邮箱是否存在
        $adminCount = Admin::where('username',$request->username)
                            ->orWhere('email', $request->email)
                            ->count();
        if ($adminCount > 0){
            return redirect()->back()->withErrors("用户名或邮箱已存在，不可重复添加。")->withInput($request->all());
        }
        $admin = Admin::query()->find(session('uid'));
        // 保存菜单数据
        $data = New Admin;
        $data->username = $request->username;
        $data->password = bcrypt($request->password); //加密处理
        $data->fullName = $request->fullName;
        $data->email = $request->email;
        $data->status = $request->status;
//        if($request->group_id)
//            $data->group_id = $request->group_id;
        if($request->group_id){
            $data->group_id = $request->group_id;
        }else{
            $data->group_id = $admin['group_id'];
        }

        $data->save();

        // 记录管理员操作日志
        if(config('mbcore_baseadmin.baseadmin_admin_log_add')) {
            $params = [
                'admin_id' => session('uid'),
                'operation' => '添加管理员',
                'ip' => $_SERVER['REMOTE_ADDR'],
            ];
            Helper::addAdminLog($params);
        }
        return redirect()->back()->withErrors("is_save_success")->withInput([]);
    }

    public function list(Request $request){
        $admins = Admin::get();
        //dd($admins)

        // 权限
        $system_roles = $this->getSystemRoles();
        //dd($system_roles);
        $menu_roles = $this->getMenuRoles();
        //dd($menu_roles);

        //当前路由的名称
        //$routeAction = $request->route()->getAction();
        //dd($routeAction);

        $roles = ['system'=>$system_roles,'menu'=>$menu_roles];
        //dd($roles);
        $rolesJson = json_encode(array_values($roles));

        //读取数据库的权限信息
        $this->setRolesArr($request);
        return view('mbcore.baseadmin::admin.list',['data'=>$admins,'rolesJson'=>$rolesJson]);
    }

    // Api  管理员编辑
    public function editsave(Request $request){

        $validator = \Validator::make($request->all(),[
            'id' => 'required',
            'fullName' => 'required',
            'email' => 'required|email',
        ], [
            'fullName.required' => '姓名不能为空',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确',
        ]);
        if ($validator->fails()) {
            //return redirect()->back()->withErrors($validator)->withInput();
            $warnings = $validator->messages();
            $show_warning = $warnings->first();
            return $this->retErr($show_warning);
        }

        $admin = Admin::find($request->id);
        if(!$admin){
            return $this->retErr("操作异常，此ID账号不存在。");
        }
        $admin->fullName = $request->fullName;
        $admin->email = $request->email;
        if(!empty($request->password)){
            $admin->password = bcrypt($request->password);
        }

        // 保存
        try{
            $admin->save();
            return $this->ret("修改成功");
        }catch (\Exception $e){
            return $this->retErr($e->getMessage());
        }


    }

    //Api  管理员权限获得
    public function getRole(Request $request){
        $admin_id = intval($request->id);
        if(empty($admin_id)){
            return $this->retErr('参数异常！');
        }
        $admin = Admin::find($admin_id);
        if(!$admin){
            return $this->retErr("操作异常，此ID账号不存在。");
        }
        //正常返回
        $roles = $admin->roles;
        if(!$roles) {
            $roles = [
                'system'=>[],
                'menu'=>[]
            ];
            $roles = json_encode($roles);
        }
        return $this->ret($roles);
    }

    //Api 保存管理员权限
    public function saveRole(Request $request){
        //验证管理员
        $admin_id = intval($request->id);
        if(empty($admin_id)){
            return $this->retErr('参数异常！');
        }
        $admin = Admin::find($admin_id);
        if(!$admin){
            return $this->retErr("操作异常，此ID账号不存在。");
        }
        //进行逻辑处理
        $systemRoles = $request->get("systemRoles",[]);
        $menuRoles = $request->get("menuRoles",[]);
        $data = [
            'system'=>$systemRoles,
            'menu'=>$menuRoles
        ];
        $dataJson = json_encode($data);

        $admin->roles = $dataJson;
        // 保存
        try{
            //刷新设置session，更新权限验证内容
            $this->setUserRolesSession($request,$dataJson);

            $admin->save();
            return $this->ret("修改成功");
        }catch (\Exception $e){
            return $this->retErr($e->getMessage());
        }

        //return $this->ret($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     *
     * 修改密码
     */
    public function change_password(Request $request){
        $uid = session('uid');
        $admin = Admin::query()->find($uid);

        if($request->isMethod('post')){
            $old_password = $request->get('old_password',null);
            if($old_password) {
                if (!password_verify($old_password,$admin->password)) {
                    return redirect()->back()->withErrors('原密码不正确')->withInput();
                }
            }
            $new_password = $request->get('new_password',null);
            $confirm_password = $request->get('confirm_password',null);
            if($new_password && $confirm_password){
                if($confirm_password !=$new_password){
                    return redirect()->back()->withErrors('新密码和确认密码不一致')->withInput();
                }
            }
            $validator = \Validator::make($request->all(),[
                'old_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required',
            ], [
                'old_password.required' => '请输入原密码',
                'new_password.required' => '请输入新密码',
                'confirm_password.required' => '请输入确认密码',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            \DB::beginTransaction();
            try{
                $admin->password = bcrypt($new_password);
                $admin->save();

                \DB::commit();
                return redirect()->back()->withErrors("success")->withInput();
            }Catch(\Exception $e){
                \DB::rollBack();

                throw new \Exception($e->getMessage());
            }

        }
        $subtitle = '修改密码';
        $compact = compact('subtitle');
        return view('mbcore.baseadmin::admin.password.change',$compact);
    }


}