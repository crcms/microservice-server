<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2019-01-28 22:30
 *
 * @link http://crcms.cn/
 *
 * @copyright Copyright &copy; 2019 Rights Reserved CRCMS
 */

namespace CrCms\Microservice\Server\Contracts;

use Illuminate\Contracts\Debug\ExceptionHandler as BaseExceptionHandlerContract;
use Exception;

interface ExceptionHandlerContract extends BaseExceptionHandlerContract
{
    /**
     * Report or log an exception.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e);

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render(RequestContract $request, Exception $e): ResponseContract;

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @param  \Exception  $e
     * @return void
     */
    public function renderForConsole($output, Exception $e);
}