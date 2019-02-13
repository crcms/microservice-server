<?php

namespace CrCms\Microservice\Server\Contracts;

use CrCms\Microservice\Dispatching\Matcher;

interface RequestContract
{
    /**
     * @return Matcher
     */
    public function caller(): Matcher;

    /**
     * @param Matcher $caller
     * @return RequestContract
     */
    public function setCaller(Matcher $caller): self;

    /**
     * @return mixed
     */
    public function rawData();

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param array $data
     *
     * @return RequestContract
     */
    public function setData(array $data): self;
}
