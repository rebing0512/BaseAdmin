<?php
namespace Jenson\BaseAdmin\Libraries;

use Illuminate\Http\Request;
use Jenson\BaseAdmin\Models\Admin;
use Jenson\BaseAdmin\Models\AdminLog;
use Jenson\BaseAdmin\Models\Menu;

class Helper
{

    //系统级别权限设定
    protected static $system_roles = [
        ['name'=>'菜单管理','flag'=>'menu'],
        ['name'=>'主页','flag'=>'home','subroles'=>[
            ['name'=>'超级管理员','flag'=>'home_1'],
            ['name'=>'高级管理员','flag'=>'home_2'],
            ['name'=>'中级管理员','flag'=>'home_3'],
            ['name'=>'低级管理员','flag'=>'home_4'],
        ]
        ],
        ['name'=>'管理员管理','flag'=>'admin','subroles'=>[
            ['name'=>'账号增加','flag'=>'admin_add'],
            ['name'=>'账号列表','flag'=>'admin_list'],
            ['name'=>'密码管理','flag'=>'admin_password'],
            ['name'=>'授权管理','flag'=>'admin_roles'],
        ]
        ]
    ];



    // 系统级别权限
    public static function getSystemRoles(){
        $systemRoles = Helper::$system_roles;

        //如果设置了管理员首页显示分级
        if(config('mbcore_baseadmin.baseadmin_roles_home_subroles')){
            $systemRoles[1]['subroles'] = config('mbcore_baseadmin.baseadmin_roles_home_subroles');
        }
        //if(!config('mbcore_baseadmin.baseadmin_development')) {
        //    array_shift($systemRoles);
        //}
        //dd($systemRoles);
        return $systemRoles;
    }
    // 菜单级别权限
    public static function getMenuRoles(){
        $menuRoles = [];
        $menus = Menu::orderBy('sort', 'desc')->get(["id","name","parent_id","group_id"])->toArray();
        //dd($menus);
        $tempMenus = [];
        foreach($menus as $menu){
            if($menu['parent_id']>0) {
                $tempMenus[$menu['parent_id']]['subroles'][] = ['name' => $menu['name'], 'flag' => $menu['id']];
            }else {
                $tempMenus[$menu['id']]['name'] = $menu['name'];
                $tempMenus[$menu['id']]['flag'] = $menu['id'];
                $tempMenus[$menu['id']]['group_id'] = $menu['group_id'];
            }
        }
        //dd($tempMenus);


        // 如果分组
        if(config('mbcore_baseadmin.baseadmin_menuGroup')){
            foreach (config('mbcore_baseadmin.baseadmin_menuGroup') as $key=>$val){
                $menuRoles[$key]["name"] = $val;
                $menuRoles[$key]["flag"] = "G".$key;
            }
            foreach($tempMenus as $menu){
                $menuRoles[$menu['group_id']]["subroles"][] =$menu;
            }
        }else{
            $menuRoles =  array_values($tempMenus); //$tempMenus;
        }
        //dd($menuRoles);
        return $menuRoles;
    }


    /*
     *  是否具有权限的判断
     */
    public static function hasRoles($expression,$rolesArr) {
        if(!is_array($rolesArr) && $rolesArr=="is_super_user"){
            return true;
        }else{
            if(is_array($rolesArr)){
                if(is_array($expression)){
                    $flag = false;
                    foreach($expression as $val){
                        $flag = in_array($val,$rolesArr);
                        if($flag) break;
                    }
                    return $flag;
                }else{
                    return in_array($expression,$rolesArr);
                }
            }else{
                return true;
            }
        }
    }

    //判断是否为超级用户
    public static function isSuperUser($expression){
        if(!is_array($expression) && $expression=="is_super_user"){
            return true;
        }else{
            return false;
        }
    }

    public static function getUserHome(Request $request){
        $rolesData = Admin::find($request->session()->get("uid"))->toArray();
        $rolesJson = $rolesData["roles"];
        //dd($rolesJson);

        $homeName = "";
        $HomeNameArr = Helper::getSystemRoles()[1]['subroles'];
        if($rolesJson == "is_super_user"){
            $homeName = $HomeNameArr[0]['flag'];
        }else{
            $rolesSystem = json_decode($rolesJson,true)['system'];
            if(in_array("home",$rolesSystem)){
                $tempName = "";
                foreach($HomeNameArr as $val){
                    if(in_array($val['flag'],$rolesSystem)) {
                        $homeName = $val['flag'];
                    }
                    $tempName = $val['flag'];
                }
                if(empty($homeName)) $homeName = $tempName;
            }else{
                $homeName = false;
            }
        }
        //dd($rolesArr);
        return $homeName;
    }

    public static function hasVisterRoles(Request $request){
         $role = $request->session()->get("roles",[]);

         //超级管理员
         if($role == 'is_super_user'){
             return true;
         }
         //dd($role);
         if(empty($role)){
             return false;
         }

         $roleArr = json_decode($role,true);
         //dd(gettype($roleArr));
         //dd($role);
        //当前路由的名称
        $routeAction = $request->route()->getAction();
        $roteName = $routeAction['as'];
        //dd($roteName);
        if(!empty($roteName) && in_array($roteName,$roleArr)){
            return true;
        }else{
            //dd("0000");
            return false;
        }
    }


    //判断是否登录
    public static function isLogin(Request $request){
        if ($request->session()->has('is_login')) {
            //进入下一层请求
            return redirect()->route('admin.default');
        } else {
            return redirect()->route('login.login');
        }
    }

    /**
     * @param array $params
     * @return array
     * @throws \Exception
     *
     * Admin Log Add
     */
    public static function addAdminLog(array $params){
        if(empty($params))
            return [
                'code'=>0,
                'result'=>[
                    'msg'=>'系统异常'
                ]
            ];

        $create = [
            'admin_id'=>$params['admin_id'],
            'operation'=>$params['operation'],
            'ip'=>$params['ip'],
        ];
        \DB::beginTransaction();
        try{
            AdminLog::query()->create($create);
            \DB::commit();

            return [
                'code'=>1,
                'result'=>[
                    'msg'=>'success'
                ]
            ];;
        }Catch(\Exception $e){
            \DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

}