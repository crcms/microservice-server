<?php

namespace CrCms\Microservice\Server\Http\Events;

use CrCms\Microservice\Server\Contracts\KernelContract as Kernel;
use CrCms\Server\Server\AbstractServer;
use CrCms\Server\Drivers\Laravel\Http\Request as ServerRequest;
use CrCms\Server\Drivers\Laravel\Http\Response as ServerResponse;
use CrCms\Server\Drivers\Laravel\Http\Events\Server\RequestEvent as BaseRequestEvent;
use CrCms\Microservice\Server\Http\Request as MicroserviceRequest;

/**
 * Class RequestEvent.
 */
class RequestEvent extends BaseRequestEvent
{
    /**
     * @param AbstractServer $server
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $this->server->getLaravel()->open();

            $app = $this->server->getApplication();

            $kernel = $app->make(Kernel::class);

            $microserviceRequest = new MicroserviceRequest(
                $app,
                ServerRequest::make($this->swooleRequest)->getIlluminateRequest()
            );

            $microserviceResponse = $kernel->handle($microserviceRequest);

            ServerResponse::make($this->swooleResponse, $microserviceResponse)->toResponse();

            $kernel->terminate($microserviceRequest, $microserviceResponse);
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            // reset application
            $this->server->getLaravel()->close();
        }
    }
}
