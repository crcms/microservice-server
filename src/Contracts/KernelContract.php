<?php

namespace CrCms\Microservice\Server\Contracts;

use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

/**
 * Interface KernelContract.
 */
interface KernelContract
{
    /**
     * @return void
     */
    public function bootstrap(): void;

    /**
     * @param RequestContract $request
     * @return ResponseContract|null
     */
    public function request(RequestContract $request);

    /**
     * transport
     *
     * @param string $data
     * @param RequestContract|null $request
     * @return ResponseContract
     */
    public function transport(string $data, ?RequestContract $request = null): ResponseContract;

    /**
     * @param RequestContract|DataProviderContract $request
     * @param ResponseContract $response
     *
     * @return mixed
     */
    public function terminate($data, ResponseContract $response);

    /**
     * @return ApplicationContract
     */
    public function getApplication(): ApplicationContract;
}
