<?php

namespace CrCms\Microservice\Server\Tests;

use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use CrCms\Foundation\Transporters\DataProvider;
use CrCms\Microservice\Dispatching\Matcher;
use CrCms\Microservice\Server\Middleware\ParseCallerMiddleware;
use Illuminate\Contracts\Container\Container;
use CrCms\Microservice\Routing\Router;
use Illuminate\Support\Facades\Facade;
use CrCms\Microservice\Server\Contracts\KernelContract;
use CrCms\Microservice\Server\Contracts\RequestContract;
use CrCms\Microservice\Server\Contracts\ResponseContract;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use CrCms\Microservice\Dispatching\Pipeline;
use Exception;
use Throwable;

/**
 * Class Kernel.
 */
class Kernel implements KernelContract
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        //\CrCms\Microservice\Server\Middleware\DataEncryptDecryptMiddleware::class,
        ParseCallerMiddleware::class,
    ];

    /**
     * Create a new HTTP kernel instance.
     *
     * @param ApplicationContract $app
     * @param Router $router
     *
     * @return void
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function handle(RequestContract $request): ResponseContract
    {
        $this->app->instance('request', $this->bindRequestCaller($request));

        Facade::clearResolvedInstance('request');

        try {
            $response = (new Pipeline($this->app))
                ->send($request)
                ->through(array_merge($this->middleware,$request->caller()->getCallerMiddleware()))
                ->then(function(RequestContract $request){
                    return $this->app->call($request->caller()->getCallerUses());
//                    if (!empty($data)) {
//                        $this->app->make('server.packer')->pack($data);
//                    }
//                    return Response
                });
        } catch (Exception $e) {
            $this->reportException($e);
            $response = $this->renderException($request, $e);
        } catch (Throwable $e) {
            $this->reportException($e = new FatalThrowableError($e));
            $response = $this->renderException($request, $e);
        }

        return $response;
    }

    protected function bindRequestCaller(RequestContract $request): RequestContract
    {
        $data = $this->app->make('server.packer')->unpack($request->rawData());

        /* @var Matcher $matcher */
        $matcher = $this->app->make('dispatcher')->getCaller($data['call']);

        $request->setCaller($matcher->setContainer($this->app));
        $request->setData($data['data'] ?? []);
        return $request;
    }


    protected function bindResponse()
    {

    }

    public function bootstrap(): void
    {
        if (!$this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers());
        }
    }

    /**
     * @param RequestContract|DataProviderContract $data
     * @param ResponseContract $response
     *
     * @return mixed|void
     */
    public function terminate(RequestContract $request, ResponseContract $response)
    {
        $this->terminateMiddleware($request, $response);

        $this->app->terminate();
    }

    /**
     * @param RequestContract $request
     * @param ResponseContract $response
     */
    protected function terminateMiddleware(RequestContract $request, ResponseContract $response)
    {
        $middlewares = array_merge(
            $request->currentCall()->getCallerMiddleware(),
            $this->middleware
        );

        foreach ($middlewares as $middleware) {
            if (!is_string($middleware)) {
                continue;
            }

            list($name) = $this->parseMiddleware($middleware);

            $instance = $this->app->make($name);

            if (method_exists($instance, 'terminate')) {
                $instance->terminate($data, $response);
            }
        }
    }

    /**
     * @param $middleware
     *
     * @return array
     */
    protected function parseMiddleware($middleware)
    {
        list($name, $parameters) = array_pad(explode(':', $middleware, 2), 2, []);

        if (is_string($parameters)) {
            $parameters = explode(',', $parameters);
        }

        return [$name, $parameters];
    }

    /**
     * Determine if the kernel has a given middleware.
     *
     * @param string $middleware
     *
     * @return bool
     */
    public function hasMiddleware($middleware)
    {
        return in_array($middleware, $this->middleware);
    }

    /**
     * @return array
     */
    protected function bootstrappers()
    {
        return $this->bootstrappers;
    }

    /**
     * @param Exception $e
     */
    protected function reportException(Exception $e)
    {
        $this->app[ExceptionHandlerContract::class]->report($e);
    }

    /**
     * @param RequestContract $request
     * @param Exception $e
     *
     * @return mixed
     */
    protected function renderException(?RequestContract $request, Exception $e)
    {
        return $this->app[ExceptionHandlerContract::class]->render($request, $e);
    }

    /**
     * @return ApplicationContract
     */
    public function getApplication(): ApplicationContract
    {
        return $this->app;
    }
}
