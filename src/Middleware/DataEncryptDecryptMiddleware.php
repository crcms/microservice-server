<?php

namespace CrCms\Microservice\Server\Middleware;

use Closure;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Microservice\Bridging\DataPacker;
use CrCms\Microservice\Server\Contracts\RequestContract;
use CrCms\Microservice\Server\Contracts\ResponseContract;

/**
 * Class DataEncryptDecryptMiddleware.
 */
class DataEncryptDecryptMiddleware
{
    /**
     * @var DataPacker
     */
    protected $packer;

    /**
     * @param DataPacker $packer
     */
    public function __construct(DataPacker $packer)
    {
        $this->packer = $packer;
    }

    /**
     * @param RequestContract $request
     * @param Closure         $next
     *
     * @return mixed
     */
    public function handle(DataProviderContract $data, Closure $next)
    {
        /* @var ResponseContract $response */
        $response = $next($data);

        /* 后置执行 */
        $responseData = $response->getData(true);
        if (! empty($responseData)) {
            $response->setData($this->packer->pack($responseData));
        }

        return $response;
    }
}
