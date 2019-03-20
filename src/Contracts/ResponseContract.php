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
     * setStatusCode
     *
     * @return ResponseContract
     */
    public function setStatusCode(int $code): ResponseContract;

    /**
     * getStatusCode
     *
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * setPackData
     *
     * @param string $data
     * @return ResponseContract
     */
    public function setPackData(string $data): ResponseContract;

    /**
     * setData
     *
     * @param array $data
     * @return ResponseContract
     */
    public function setData(array $data): ResponseContract;

    /**
     * @return array
     */
    public function getData(): array;
}
