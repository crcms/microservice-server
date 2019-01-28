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
use CrCms\Microservice\Dispatching\Matcher;
use CrCms\Microservice\Server\Contracts\RequestContract;
use Closure;

class ParseCallerMiddleware
{
    /**
     * @var DataPacker
     */
    protected $packer;

    /**
     * @var Matcher
     */
    protected $matcher;

    /**
     * @param DataPacker $packer
     */
    public function __construct(DataPacker $packer, Matcher $matcher)
    {
        $this->packer = $packer;
        $this->matcher = $matcher;
    }

    /**
     * @param RequestContract $request
     * @param Closure         $next
     *
     * @return mixed
     */
    public function handle(RequestContract $request, Closure $next)
    {
        /* 前置执行 */
        $data = $this->packer->unpack($request->rawData());

        $this->matcher->match($data['call']);
        $request->setCaller($this->matcher);
        $request->setData($data['data'] ?? []);

        return $next($data);
    }
}