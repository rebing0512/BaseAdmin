<?php
namespace Jenson\BaseAdmin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Jenson\BaseAdmin\Models\Menu;
use Carbon\Carbon;

Class MenuController extends BaseController
{
    public function add(Request $request)
    {
        $topmenu = Menu::where('parent_id',0)->get();
        return view('mbcore.baseadmin::menu.add',['topmenu'=>$topmenu]);
    }

    public function addsave(Request $request)
     {
         $validator = \Validator::make($request->all(),[
             'name' => 'required',
             'sort' => 'numeric',
             'group_id' => 'numeric',
         ], [
             'name.required' => '菜单名称不能为空',
             'sort.numeric' => '排序字段必须是数字',
             'group_id.numeric' => '菜单分组异常',
         ]);
         if ($validator->fails()) {
             return redirect()->back()->withErrors($validator)->withInput();
         }

         // 如果父级ID为零,则为顶级ID
         if ($request->parent_id == 0) {
             $menuCount = Menu::where('name',$request->name)->count();
             if ($menuCount > 0){
                 return redirect()->back()->withErrors("名称为“".$request->name."”的顶级菜单已存在。")->withInput();
             }
         }else{
             $menuCount = Menu::where('id',$request->parent_id)->count();
             if($menuCount==1){
                 $menuCount = Menu::where('name',$request->name)->where('parent_id',$request->parent_id)->count();
                 if ($menuCount > 0){
                     return redirect()->back()->withErrors("名称为“".$request->name."”的子菜单在所选父级菜单下已存在。")->withInput();
                 }
             }else{
                 return redirect()->back()->withErrors("菜单父级参数异常，请重新选择提交。")->withInput();
             }
         }

         // 进行group_id处理，二级菜单分组为0
         if($request->parent_id>0){
             $group_id = 0;
         }else{
             $group_id = $request->group_id;
         }


         // 保存菜单数据
         $data = New Menu;
         $data->name = $request->name;
         $data->link = $request->link;
         $data->i_ico_class = $request->i_ico_class;
         $data->sort = $request->sort;
         $data->parent_id = $request->parent_id;
         $data->group_id = $group_id ;

         $data->save();

         return redirect()->back()->withErrors("is_save_success")->withInput();
     }

     public function list(){
        // 顶级菜单
         $menus = Menu::where('parent_id',0)->orderBy('sort', 'desc')->get(["id","name","link","sort","parent_id","group_id","i_ico_class"])->toArray();
         // 数组
         $topmenu = [];
         //临时菜单数组
         $tempMenus = [];
         //重新整理数据
         foreach ($menus as $menu){
             $topmenu[] = ['id'=>$menu['id'],'name'=>$menu['name']];
             $tempMenus[$menu["id"]]['text'] = $menu['name'];
             $tempMenus[$menu["id"]]['tags']['id'] = $menu['id'];
             $tempMenus[$menu["id"]]['tags']['hasChild'] = 0;
             $tempMenus[$menu["id"]]['tags']['isFather'] = 1;
             $tempMenus[$menu["id"]]['tags']['data'] = $menu;
         }
         //子菜单数据
         $subMenus = Menu::where('parent_id','<>',0)->orderBy('sort', 'desc')->get(["id","name","link","sort","parent_id","i_ico_class"])->toArray();
         foreach ($subMenus as $menu){
             $tempMenus[$menu["parent_id"]]['nodes'][] = ['text'=>$menu['name'],'tags'=>['id'=>$menu['id'],'data'=>$menu]];
             $tempMenus[$menu["parent_id"]]['tags']['hasChild'] = 1;
         }

         // 菜单json
         if(config('mbcore_baseadmin.baseadmin_menuGroup')){
             $echoMenus = [];
             foreach (config('mbcore_baseadmin.baseadmin_menuGroup') as $key=>$val){
                 $echoMenus[$key]["text"] ="*****【分组】：".$val."*****";
                 $echoMenus[$key]["tags"]["isGroup"] =1;
             }
             foreach($tempMenus as $menu){
                 $echoMenus[$menu['tags']['data']["group_id"]]["nodes"][] =$menu;
             }
             //dd($echoMenus);
         }else{
             $echoMenus = $tempMenus;
         }
         $menusJson = json_encode(array_values($echoMenus));

         // 顶级菜单json
         $topmenuJson = json_encode(array_values($topmenu));
         //dd($menusJson);
         //dd($topmenuJson);
         //$topmenu = json_encode(array_values($menus));
         return view('mbcore.baseadmin::menu.list',['menusJson'=>$menusJson,'topmenuJson'=>$topmenuJson]);
     }

    public function editsave(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'id' => 'required',
            'name' => 'required',
            'sort' => 'numeric',
            'group_id' => 'numeric',
        ], [
            'id.required' => '菜单重要参数丢失请重试',
            'name.required' => '菜单名称不能为空',
            'sort.numeric' => '排序字段必须是数字',
            'group_id.numeric' => '菜单分组异常',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 如果父级ID为零,则为顶级ID
        if ($request->parent_id == 0) {
            // 验证菜单是否存在
            $menuCount = Menu::where('name',$request->name)->where('id','<>',$request->id)->count();
            if ($menuCount > 0){
                return redirect()->back()->withErrors("名称为“".$request->name."”的顶级菜单已存在。")->withInput();
            }
        }else{
            // 二级菜单改成顶级菜单验证  // 验证菜单是否有子菜单
            $menuCount = Menu::where('parent_id',$request->id)->count();
            if ($menuCount > 0){
                return redirect()->back()->withErrors("名称为“".$request->name."”的顶级菜单下还有子菜单，不允许修改为二级菜单。")->withInput();
            }else{
                // 正常验证
                $menuCount = Menu::where('id',$request->parent_id)->count();
                if($menuCount==1){
                    $menuCount = Menu::where('name',$request->name)->where('parent_id',$request->parent_id)->count();
                    if ($menuCount > 0){
                        return redirect()->back()->withErrors("名称为“".$request->name."”的子菜单在所选父级菜单下已存在。")->withInput();
                    }
                }else{
                    return redirect()->back()->withErrors("菜单父级参数异常，请重新选择提交。")->withInput();
                }
            }
        }

        // 进行group_id处理，二级菜单分组为0
        if($request->parent_id>0){
            $group_id = 0;
        }else{
            $group_id = $request->group_id;
        }

        // 保存菜单数据
        $updatrArr = [
            'name' => $request->name,
            'link' => $request->link,
            'i_ico_class' => $request->i_ico_class,
            'sort' => $request->sort,
            'parent_id' => $request->parent_id,
            'group_id' => $group_id,
        ];

        try{
            Menu::where('id',$request->id)->update($updatrArr);
            return redirect()->back()->withErrors("is_save_success")->withInput();
        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

}