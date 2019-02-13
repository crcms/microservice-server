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

use CrCms\Microservice\Bridging\DataPacker;
use CrCms\Microservice\Bridging\Packer\JsonPacker;
use CrCms\Microservice\Server\Contracts\RequestContract;
use Illuminate\Support\Collection;
use CrCms\Microservice\Routing\Route;
use Illuminate\Support\ServiceProvider;
use CrCms\Microservice\Server\Http\Request;
use CrCms\Microservice\Server\Http\Response;
use CrCms\Microservice\Server\Events\RequestHandling;
use CrCms\Microservice\Server\Contracts\ResponseContract;

/**
 * Class ServerServiceProvider.
 */
class ServerServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @return void
     */
    public function boot(): void
    {
//        $this->app['events']->listen(RequestHandling::class, function (RequestHandling $event) {
//            if ($event->request instanceof Request && $event->request->method() !== 'POST') {
//                return $this->allServices();
//            }
//        });

        //merge server config to swoole config
        $this->mergeServerConfigToSwoole();
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->registerAlias();

        //$this->registerServices();

//        $this->app->bind('response',Response::class);
    }

    /**
     * @return void
     */
    protected function registerAlias(): void
    {
        $this->app->alias('server.packer', DataPacker::class);
        $this->app->alias('request',RequestContract::class);
//        $this->app->alias('response',ResponseContract::class);
//        'request' => [\CrCms\Microservice\Server\Contracts\RequestContract::class],
//                     'caller' => [\CrCms\Microservice\Dispatching\Dispatcher::class],
//                     'caller.match' => [\CrCms\Microservice\Dispatching\Matcher::class],
    }

    /**
     * @return void
     */
    protected function registerServices(): void
    {
        $this->app->singleton('server.packer', function ($app) {
            $encryption = $app['config']->get('app.encryption');
            return new DataPacker(new JsonPacker, $encryption === true ? $app['encrypter'] : null);
        });
    }

    /**
     * @return ResponseContract
     */
    protected function allServices(): ResponseContract
    {
        $methods = (new Collection($this->app->make('router')->getRoutes()->get()))->mapWithKeys(function (Route $route) {
            $uses = $route->getAction('uses');
            $uses = $uses instanceof \Closure ? 'Closure' : $uses;

            return [$route->mark() => $uses];
        })->toArray();

        return new Response(['methods' => $methods], 200);
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

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            'server.packer',
        ];
    }
}
