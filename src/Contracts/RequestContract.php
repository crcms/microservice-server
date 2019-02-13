<?php

namespace CrCms\Microservice\Server\Contracts;

use CrCms\Microservice\Dispatching\Matcher;

interface RequestContract
{
    /**
     * @return Matcher
     */
    public function matcher(): Matcher;

    /**
     * @param Matcher $matcher
     * @return RequestContract
     */
    public function setMatcher(Matcher $matcher): self;

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
