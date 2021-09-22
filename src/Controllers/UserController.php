<?php
namespace Jenson\BaseAdmin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jenson\BaseUser\Models\User;
use Carbon\Carbon;
use Jenson\BaseCore\Libraries\Helper as CoreHelper;

Class UserController extends JensonBaseAdminController
{
    /**
     * @param Request $request
     * @return mixed
     *
     * lists
     */
    public function lists(Request $request){
        $condition = $request->get('condition','username');
        $pageSize = 10;
        if($request->isMethod('post')){

            $query = User::query()->orderBy('created_at', 'desc');

            $keywords = $request->get('keywords',null);
            if(!empty($keywords)){
                if($condition == 'username'){// 用户名
                    $query->where('username','like','%'.$keywords.'%');
                }elseif($condition == 'phone'){// 手机号
                    $query->where('phone','like','%'.$keywords.'%');

                }
            }
            // 注册方式
            $type =$request->get("type",'all');
            if($type != 'all'){
//                $query->where('status',$type);
                $query->where('register_method',$type);
            }

            //limit	10        page	0
            $pageSize = $request->get('limit',$pageSize);
            // 计算offset值
            $offset = $request->get('offset',0);
            //总数

            $total = $query->count();

            $data = $query->limit($pageSize)->offset($offset)->get()->toArray();

            return $this->ret(['total'=>$total,'data'=>$data]);
        }
        $data = User::query()->limit($pageSize)->orderBy('created_at', 'desc')->get()->toArray();
        $subtitle = '用户列表';
        return view('mbcore.baseadmin::user.list',compact('data','subtitle'));
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     *
     * Add
     */
    public function add(Request $request)
    {
        if($request->isMethod('post')){
            $phone = $request->get('phone');
            if(!empty($phone)){
                $vp = CoreHelper::telephoneNumber($phone,true);
                if($vp['code'] == 0){

                    return redirect()->back()->withErrors($vp['msg'])->withInput($request->all());
                }
            }

            # 输入邮箱时验证邮箱否则不验证
            if($request->get('email')){
                $validator = \Validator::make($request->all(),[
                    'username' => 'required|unique:mbuser_users',
                    'password' => 'required',
                    'phone' => 'required|unique:mbuser_users',
                    'email' => 'email|unique:mbuser_users',
                ], [
                    'username.required' => '用户名不能为空',
                    'username.unique' => '用户名已存在',
                    'password.required' => '密码不能为空',
                    'phone.required' => '手机号不能为空',
                    'phone.unique' => '手机号已存在',
                    'email.email' => '邮箱格式不正确',
                    'email.unique' => '邮箱已存在',
                ]);
            }else{
                $validator = \Validator::make($request->all(),[
                    'username' => 'required|unique:mbuser_users',
                    'password' => 'required',
                    'phone' => 'required|unique:mbuser_users',
                ], [
                    'username.required' => '用户名不能为空',
                    'username.unique' => '用户名已存在',
                    'password.required' => '密码不能为空',
                    'phone.required' => '手机号不能为空',
                    'phone.unique' => '手机号已存在',
                ]);
            }
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput($request->all());
            }

            $create = [
                'username'=>$request->get('username'),
                'password'=>bcrypt($request->get('password')),
                'phone'=>$request->get('phone'),
                'email'=>$request->get('email'),
                'register_method'=>2,
                'roles'=>$request->get('roles','is_super_user'),
                'fullName'=>$request->get('fullName')
            ];
            \DB::beginTransaction();
            try{
                User::query()->create($create);
                \DB::commit();
                return redirect()->back()->withErrors("success")->withInput();
            }Catch(\Exception $e){
                \DB::rollBack();
                throw new \Exception($e->getMessage());

            }

        }
        $subtitle = '添加用户';
        return view('mbcore.baseadmin::user.add',compact('subtitle'));
    }

    /**
     * @param Request $request
     * @param $id
     * @param User $user
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     *
     * Edit
     */
    public function edit(Request $request ,$id ,User $user)
    {
        $data = $user->find($id);
        if($request->isMethod('post')){
            $phone = $request->get('phone');
            if(!empty($phone)){
                $vp = CoreHelper::telephoneNumber($phone,true);
                if($vp['code'] == 0){
                    return redirect()->back()->withErrors($vp['msg'])->withInput();
                }

                if($phone != $data->phone){
                    $up = $user->where('phone',$phone)->first();
                    if($up){
                        return redirect()->back()->withErrors('手机号已存在')->withInput();
                    }
                }
            }

            $username = $request->get('username');
            if(!empty($username)){
                if($username != $data->username){
                    $up = $user->where('username',$username)->first();
                    if($up){
                        return redirect()->back()->withErrors('用户名已存在')->withInput();
                    }
                }
            }

            $email = $request->get('email');
            if(!empty($email)){
                if($email != $data->email){
                    $ue = $user->where('email',$email)->first();
                    if($ue){
                        return redirect()->back()->withErrors('邮箱已存在')->withInput();
                    }
                }
            }

            $validator = \Validator::make($request->all(),[
                'username' => 'required',
                'phone' => 'required',
                'email' => 'email',
            ], [
                'username.required' => '用户名不能为空',
                'username.unique' => '用户名已存在',
                'phone.required' => '手机号不能为空',
                'email.email' => '邮箱格式不正确',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput($request->all());
            }

            $password = $request->get('password','');
            if($password){
                $password = bcrypt($request->get('password'));
            }else{
                $password = $data->password;
            }
            $update = [
                'username'=>$username,
                'password'=>$password,
                'phone'=>$phone,
                'email'=>$request->get('email'),
                'fullName'=>$request->get('fullName'),
                'roles'=>$request->get('roles',$data['roles'])
            ];
            \DB::beginTransaction();
            try{
                User::query()->where('id',$id)->update($update);
                \DB::commit();
                return redirect()->back()->withErrors("success")->withInput();
            }Catch(\Exception $e){
                \DB::rollBack();
                throw new \Exception($e->getMessage());

            }

        }
        $subtitle = '编辑用户';
        return view('mbcore.baseadmin::user.edit',compact('data','subtitle'));
    }

    /**
     * @param $id
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     *
     * Lock User
     */
    public function lockUser($id, User $user)
    {
        $user = $user->find($id);

        try{
            if(!$user){
                return $this->retErr("用户不存在！",2);
            }
            if($user->status == 1){
                $user->status = 2;// 锁定
                $msg = '锁定成功！';

            }else{
                $user->status = 1;// 解锁
                $msg = '解锁成功！';
            }
            $user->save();
            return $this->ret($msg);

        }catch (\Exception $e){
            return $this->retErr($e->getMessage());
        }

    }

}