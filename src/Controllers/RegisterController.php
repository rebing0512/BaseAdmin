<?php
namespace Jenson\BaseAdmin\Controllers;

use Jenson\BaseAdmin\Controllers\JensonBaseAdminController;
use Illuminate\Http\Request;

use MBCore\BaseUser\Models\User;
use MBCore\MCore\Libraries\Helper as MCHelper;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

Class RegisterController extends JensonBaseAdminController
{

    /**
     * Register Page
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $success = $request->get('success',0);
        return view('mbcore.baseadmin::register.index',compact('success'));
    }

    public function auth(Request $request)
    {
        $password = $request->get('password');
        $confirm_password = $request->get('confirm_password');
        if(!empty($password) && !empty($confirm_password)){
            if($confirm_password != $password){
                return redirect()->back()->withErrors(["两次密码输入不一致"])->withInput();
            }
        }
        $phone = $request->get('phone');
        if(!empty($phone)){
            $vp = MCHelper::telephoneNumber($phone,true);
            if($vp['code'] == 0){
                return redirect()->back()->withErrors($vp['msg'])->withInput();
            }
        }

        $validator = \Validator::make($request->all(),[
            'username' => 'required|unique:mbuser_users',
            'password' => 'required',
            'phone' => 'required|unique:mbuser_users',
            'code' => 'required',
            'confirm_password' => 'required',
        ], [
            'username.required' => '用户名不能为空',
            'username.unique' => '用户名已存在',
            'password.required' => '密码不能为空',
            'confirm_password.required' => '确认密码不能为空',
            'phone.required' => '手机号不能为空',
            'phone.unique' => '手机号已存在',
            'code.required' => '验证码不能为空'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $code = $request->get('code');
        $appName = strtoupper(config('mbcore_mcore.app_name'));
        $cacheKey = md5('SMS'.$phone.$appName.'-REGISTER');
        $captcha = \Cache::get($cacheKey);
        if(empty($captcha)){
            return redirect()->back()->withErrors('验证码已失效')->withInput();
        }
        if($code != $captcha){
            return redirect()->back()->withErrors('验证码错误')->withInput();
        }
        $registerProtocol = $request->get('registerProtocol');
        if($registerProtocol == 0){
            return redirect()->back()->withErrors('您还未同意注册协议')->withInput();
        }
        $create = [
            'username'=>$request->get('username'),
            'password'=>bcrypt($request->get('password')),
            'phone'=>$request->get('phone'),
            'roles'=>'is_super_user', //todo:需要优化
        ];
        \DB::beginTransaction();
        try{
            User::query()->create($create);

            \DB::commit();
            \Cache::forget($cacheKey);
            return redirect()->route('admin.register.index',['success'=>1]);
//            return redirect()->route('user.login.login',['register'=>'success']);
        }Catch(\Exception $e){
            \DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     * Send Code
     */
    public function getCode(Request $request){
//        $client = new Client();
        $client = new Client(['verify'=> false]);
//         \Log::error("ERROR:".static::class.":{$e->getMessage()}:{$e->getFile()}:{$e->getLine()}");
         \Log::error("TIME1:".Carbon::now());
        $phone = $request->get('phone',null);
        if(empty($phone)){
            return $this->retErr('请输入手机号！');
        }
        if(!empty($phone)){
            $vp = MCHelper::telephoneNumber($phone,true);
            if($vp['code'] == 0){
                return $this->retErr($vp['msg']);
            }
        }
        $type = $request->get('type','REGISTER');
        $loginSign = $request->get('loginSign',1);
        $data = User::query()->where('phone',$phone)->first();
        if($type == 'REGISTER'){ //注册 需要验证手机号是否已注册-----已注册-----不发验证码
            if($data){
                return $this->retErr('手机号已注册！');
            }
        }else{ // 登录 需要验证手机号是否已注册----未注册-----不发验证码
            if($loginSign == 1){ //登录和注册一起时不做此验证
                if(!$data){
                    return $this->retErr('手机号未注册！');
                }
            }elseif($loginSign == 2){ //登录和注册一起时，且需要走API中转服务
                $appName = strtoupper(config('mbcore_mcore.app_name'));
                $cacheKey = md5('SMS'.$phone.$appName.'-'.$type);
                \Cache::put(md5('SMS'.$phone.$appName.'-TIMES-'.$type),10);
                $code = \Cache::get($cacheKey);
                if (!$code) {
                    $randStr = str_shuffle('1234567890');
                    $code = substr($randStr,0,6);
                    \Cache::put($cacheKey,$code,Carbon::now()->addMinute(10));
                }
                $vars = [
                    'code'  => $code,
                    'time' =>  10,
                ];

                $param = [
                    'appid' => config('mbcore_baseuser.sms.key.appid'),
                    'signature' => config('mbcore_baseuser.sms.key.appkey'),
                    'project' => config('mbcore_baseuser.sms.templates.id'),
                    'to' => $phone,
                    'vars' => json_encode($vars),
                    'timeout'=>10,
                    'url'=>'https://api.mysubmail.com/message/xsend.json'
                ];

                $response = $client->post('https://pmservice.mbcore.com/api/getCode',[
                    'form_params' => [
                        'param'=>json_encode($param)
                    ],
                ]);
                $response = $response->getBody()->getContents();
                $response = json_decode($response,1);

                if($response['code'] == 1){
                    $time = 60;
                    return $this->ret([
                        'time'=>$time
                    ]);
                }
            }
        }
        $appName = strtoupper(config('mbcore_mcore.app_name'));
        $cacheKey = md5('SMS'.$phone.$appName.'-'.$type);
        \Cache::put(md5('SMS'.$phone.$appName.'-TIMES-'.$type),10);
        $code = \Cache::get($cacheKey);
        if (!$code) {
            $randStr = str_shuffle('1234567890');
            $code = substr($randStr,0,6);
            \Cache::put($cacheKey,$code,Carbon::now()->addMinute(10));
        }
        $vars = [
            'code'  => $code,
            'time' =>  10,
        ];
        \Log::error("TIME2:".Carbon::now());
        $body = [
            'appid' => config('mbcore_baseuser.sms.key.appid'),
            'signature' => config('mbcore_baseuser.sms.key.appkey'),
            'project' => config('mbcore_baseuser.sms.templates.id'),
            'to' => $phone,
            'vars' => json_encode($vars)
        ];

        $response = $client->post('https://api.mysubmail.com/message/xsend.json',[
            'form_params' => $body,
            'timeout'=>10
        ]);

        // todo：暂时不走队列
//        dispatch(new SendSmsNotification([
//            'to' => $phone,
//            'vars' => [
//                'code' => $code,
//                'time' => '10'
//            ],
//
//        ]));
        $time = 60;
        return $this->ret([
            'time'=>$time
        ]);
    }



}