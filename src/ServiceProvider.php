<?php

namespace Jenson\BaseAdmin;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Schema;
use Blade;

//use Jenson\BaseAdmin\Console\Commands\Command;

class ServiceProvider extends BaseServiceProvider
{

    public function boot()
    {

        // 特殊字段太长报错
        Schema::defaultStringLength(191);

        // 模板机制中使用的量
        Blade::directive('getLinkUrl', function($expression) {
            return "<?php echo Route($expression); ?>";
        });


        // 【1】模板
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'mbcore.baseadmin');
        //发布视图到resources/views/vendor目录
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('/views/Jenson/BaseAdmin'),
        ]);

        // 【2】路由
        $this->setupRoutes($this->app->router);

        // 【3】配置
        $this->mergeConfigFrom(
            __DIR__ . '/config/mbcore_baseadmin.php', 'mbcore_baseadmin'
        );
        //发布配置文件
        $this->publishes([
            __DIR__.'/config/mbcore_baseadmin.php' => config_path('mbcore_baseadmin.php'),
        ], 'config');

        // 【4】数据库迁移
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // 【5】资源文件
        $this->publishes([
            __DIR__.'/resources/assets' => public_path('assets/Jenson/BaseAdmin'),
        ], 'public');

        // 【6】注册 Artisan 命令
        if ($this->app->runningInConsole()) {
            $this->commands([
//                Command::class,
            ]);
        }

    }

    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Jenson\BaseAdmin\Controllers'], function($router)
        {
            require __DIR__ . '/routes/routes.php';
        });
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /** @noinspection SpellCheckingInspection */
        config([
            'config/mbcore_baseadmin.php',
        ]);
    }
}
