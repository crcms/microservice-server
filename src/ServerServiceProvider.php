<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-11-14 23:17
 *
 * @link http://crcms.cn/
 *
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Microservice\Server;

use CrCms\Microservice\Server\Contracts\RequestContract;
use CrCms\Microservice\Server\Http\Response;
use Illuminate\Support\ServiceProvider;
use CrCms\Microservice\Server\Contracts\ResponseContract;

/**
 * Class ServerServiceProvider.
 */
class ServerServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        //merge server config to swoole config
        $this->mergeServerConfigToSwoole();
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->registerAlias();

        $this->app->bind('response', Response::class);
    }

    /**
     * @return void
     */
    protected function registerAlias(): void
    {
        $this->app->alias('request', RequestContract::class);
        $this->app->alias('response', ResponseContract::class);
    }

    /**
     * @return void
     */
    protected function mergeServerConfigToSwoole(): void
    {
        $server = $this->app['config']->get('server', []);
        $swoole = $this->app['config']->get('swoole', []);
        $this->app['config']->set(['swoole' => array_merge($swoole, $server)]);
    }
}
