<?php

namespace CrCms\Microservice\Server\Http;

use CrCms\Microservice\Dispatching\Matcher;
use Illuminate\Http\Request as BaseRequest;
use Illuminate\Contracts\Container\Container;
use CrCms\Microservice\Server\Contracts\RequestContract;

/**
 * Class Request.
 */
class Request implements RequestContract
{
    /**
     * @var BaseRequest
     */
    protected $request;

    /**
     * @var Container
     */
    protected $app;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var Matcher
     */
    protected $matcher;

    /**
     * Request constructor.
     *
     * @param BaseRequest $request
     */
    public function __construct(Container $app, BaseRequest $request)
    {
        $this->app = $app;
        $this->request = $request;
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
        return $this->request->getContent();
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
