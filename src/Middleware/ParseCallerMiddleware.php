<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2019-01-28 22:20
 *
 * @link http://crcms.cn/
 *
 * @copyright Copyright &copy; 2019 Rights Reserved CRCMS
 */

namespace CrCms\Microservice\Server\Middleware;

use CrCms\Microservice\Bridging\DataPacker;
use CrCms\Microservice\Dispatching\Dispatcher;
use CrCms\Microservice\Dispatching\Matcher;
use CrCms\Microservice\Server\Contracts\RequestContract;
use Closure;
use Illuminate\Contracts\Container\Container;

class ParseCallerMiddleware
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var DataPacker
     */
    protected $packer;

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @param DataPacker $packer
     */
    public function __construct(Container $container, DataPacker $packer, Dispatcher $dispatcher)
    {
        $this->container = $container;
        $this->packer = $packer;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param RequestContract $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(RequestContract $request, Closure $next)
    {
        /* 前置执行 */
        $data = $this->packer->unpack($request->rawData());

        /* @var Matcher $matcher */
        $matcher = $this->dispatcher->getCaller($data['call']);

        $request->setCaller($matcher->setContainer($this->container));
        $request->setData($data['data'] ?? []);

        return $next($data);
    }
}