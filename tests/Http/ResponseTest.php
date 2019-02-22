<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2019-02-22 23:36
 *
 * @link http://crcms.cn/
 *
 * @copyright Copyright &copy; 2019 Rights Reserved CRCMS
 */

namespace CrCms\Microservice\Server\Tests\Http;

use CrCms\Microservice\Server\Http\Response;
use CrCms\Microservice\Server\Tests\ApplicationTrait;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    use ApplicationTrait;

    public function testResponse()
    {
        $response = new Response();

        $data = ['x'=>1,'y'=>2];
        $response->setData($data);

        $this->assertEquals($data,$response->getData());

        $response->setPackData('abc');

        $this->assertEquals('abc',$response->getContent());
    }
}