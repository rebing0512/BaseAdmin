<?php
namespace MBCore\BaseAdmin\Console\Commands;

use Illuminate\Console\Command as BaseCommand;

use MBCore\BaseAdmin\Models\Admin;
use MBCore\BaseAdmin\Models\Menu;

class Command extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mbcore:baseadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MBCore BaseAdmin Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //输出信息
        //$this->info('MBCore BaseAdmin Command Screen Info.');
        $this->echoInfo('-----------------------------');
        $this->echoInfo('If the CMD executes the code under Window, please Enter: CHCP 65001 press Enter.');
        //如果Window下CMD执行乱码，请输入：CHCP 65001 按Enter键
        $this->echoInfo('-----------------------------');

        $this->echoInfo('******* MBCore BaseAdmin 【开始】 *******');
        $i = 1;
        $this->echoInfo(($i++).": ".'添加Admin测试数据');
        // 添加Admin测试数据

        try{
            // 'username','email','password','fullName','roles','last_login_time','last_login_ip','status','confirm_email','remember_token'
            $AdminCount = Admin::where(['username' => 'admin'])->count();
            if($AdminCount>0){
                $this->echoInfo(($i++).": ".'测试账号名称已存在，操作忽略。');
                //测试账号名称已存在，操作忽略。
            }else{
                Admin::create([
                    'username' => 'admin',
                    'email' => 'i@mbcore.com',
                    'password' => bcrypt('admin'),
                    'fullName' => 'MBCore.COM',
                    'roles' => 'is_super_user'
                ]);
                $this->echoInfo(($i++).": ".'Admin测试数据添加成功，测试账号admin，密码admin。');
                //Admin测试数据添加成功，测试账号admin，密码admin。
            }

        }catch (\Exception $e){
            $this->echoInfo(($i++).": ".'【Err】Admin测试数据添加失败，'. $e->getMessage());
            //【Err】Admin测试数据添加失败，
        }

        try{
            //'parent_id','name','i_ico_class','link','sort','status'
            $MenuCount = Menu::where(['parent_id' => 0,'name' => '测试菜单test'])->count();
            if($MenuCount>0) {
                $this->echoInfo(($i++).": ".'测试菜单test 称已存在，操作忽略。'.$MenuCount);
            }else{
                Menu::create([
                    'parent_id' => 0,
                    'group_id' => 0,
                    'name' => '测试菜单test',
                    'i_ico_class' => 'glyphicon glyphicon-hand-right',
                    'link' => 'baseadmin.test'
                ]);
                $this->echoInfo(($i++).": ".'名称为【测试菜单test】的栏目添加成功。');
                //名称为【测试菜单test】的栏目添加成功。
            }


            $MenuCount = Menu::where(['parent_id' => 0,'name' => '测试菜单rolestest'])->count();
            if($MenuCount>0) {
                $this->echoInfo(($i++).": ".'测试菜单rolestest 名称已存在，操作忽略。'.$MenuCount);
            }else{
                Menu::create([
                    'parent_id' => 0,
                    'group_id' => 0,
                    'name' => '测试菜单rolestest',
                    'i_ico_class' => 'glyphicon glyphicon-hand-right',
                    'link' => 'baseadmin.rolestest'
                ]);
                $this->echoInfo(($i++).": ".'名称为【测试菜单rolestest】的栏目添加成功。');
                //名称为【测试菜单rolestest】的栏目添加成功。
            }

        }catch (\Exception $e){
            $this->echoInfo(($i++).": ".'【Err】测试菜单添加失败，'. $e->getMessage());
            //【Err】测试菜单添加失败，
        }

        $this->echoInfo('******* MBCore BaseAdmin 【结束】 *******');

    }

    private function echoInfo($data){
        //$this->info(iconv("UTF-8", "GB2312//IGNORE", $data ) );
        $this->info($data);
    }
}