<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-11-09 19:47
 *
 * @link http://crcms.cn/
 *
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Microservice\Server\Contracts;

/**
 * Interface ResponseContract.
 */
interface ResponseContract
{
    /**
     * @return mixed
     */
    public function send();

    /**
     * @return string
     */
    public function getContent(): string;

    /**
     * @param array $data
     * @return void
     */
    public function setData(array $data): void;

    /**
     * @return array
     */
    public function getData(): array;
}
