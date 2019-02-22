<?php

namespace CrCms\Microservice\Server\Http;

use CrCms\Microservice\Server\Contracts\ResponseContract;

class Response extends \Illuminate\Http\Response implements ResponseContract
{
    /**
     * @var string
     */
    protected $packData;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * setData
     *
     * @param array $data
     * @return ResponseContract
     */
    public function setData(array $data): ResponseContract
    {
        $this->data = $data;
        return $this;
    }

    /**
     * getData
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * setPackData
     *
     * @param string $data
     * @return ResponseContract
     */
    public function setPackData(string $data): ResponseContract
    {
        $this->packData = $data;
        return $this;
    }

    /**
     * getContent
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->packData;
    }
}
