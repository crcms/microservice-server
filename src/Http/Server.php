<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2019-02-22 22:46
 *
 * @link http://crcms.cn/
 *
 * @copyright Copyright &copy; 2019 Rights Reserved CRCMS
 */

namespace CrCms\Microservice\Server\Http;

use CrCms\Microservice\Server\Http\Events\RequestEvent;
use CrCms\Server\Drivers\Laravel\Http\Server as HttpServer;

class Server extends HttpServer
{
    /**
     * set server events
     *
     * @return array
     */
    protected function events(): array
    {
        $events = parent::events();
        $events['request'] = RequestEvent::class;

        return $events;
    }
}