<?php

namespace CrCms\Microservice\Server\Tcp;

use CrCms\Microservice\Dispatching\Matcher;
use CrCms\Microservice\Routing\Route;
use Illuminate\Http\Request as BaseRequest;
use Illuminate\Contracts\Container\Container;
use CrCms\Microservice\Server\Contracts\RequestContract;

class Request implements RequestContract
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $rawData;

    /**
     * @var Matcher
     */
    protected $matcher;

    /**
     * Request constructor.
     *
     * @param BaseRequest $request
     */
    public function __construct(Container $app, string $rawData)
    {
        $this->app = $app;
        $this->rawData = $rawData;
    }

    /**
     * matcher
     *
     * @return Matcher
     */
    public function matcher(): Matcher
    {
        return $this->matcher;
    }

    /**
     * setMatcher
     *
     * @param Matcher $matcher
     * @return RequestContract
     */
    public function setMatcher(Matcher $matcher): RequestContract
    {
        $this->matcher = $matcher;

        return $this;
    }

    /**
     * @return string
     */
    public function rawData()
    {
        //running in swoole
        return $this->rawData;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->data ?? [];
    }

    /**
     * @param null $key
     * @param null $default
     *
     * @return array|null|string
     */
    public function input($key = null, $default = null)
    {
        return data_get($this->data ?? [], $key, $default);
    }

    /**
     * @param array $data
     *
     * @return RequestContract
     */
    public function setData(array $data): RequestContract
    {
        $this->data = $data;

        return $this;
    }
}
