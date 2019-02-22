<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2019-02-22 23:32
 *
 * @link http://crcms.cn/
 *
 * @copyright Copyright &copy; 2019 Rights Reserved CRCMS
 */

namespace CrCms\Microservice\Server\Tests\Http;

use CrCms\Microservice\Server\Http\Request;
use CrCms\Microservice\Server\Tests\ApplicationTrait;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    use ApplicationTrait;

    public function testRequest()
    {
        $request = new Request(static::$app,new \Illuminate\Http\Request(
            [],[],[],[],[],[],'abc'
        ));

        $this->assertEquals('abc',$request->rawData());

        $data = ['x'=>1,'z'=>2];
        $request->setData($data);

        $this->assertEquals(1,$request->input('x'));
        $this->assertEquals($data,$request->all());
        $this->assertEquals($data,$request->input());
    }

}