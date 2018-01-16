<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Schema;

use Exception;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        // sentry
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
         // laravel log error check
         if (strstr($exception->getMessage(), 'storage/logs/laravel.log" could not be opened')) {
         	die('Please set write permissions for the <strong>storage</strong> folder. `chmod 777 -R storage` for terminal users.');
         }

        // not yet installed, check
        if ($exception->getCode() == 1045 && strstr($exception->getMessage(), 'Access denied for user')) {
            return redirect()->route('install.index')->with('success', 'Hey There! Let\'s get AdaptCMS Installed!');
        }

        if (!Schema::hasTable('users')) {
            return redirect()->route('install.index')->with('success', 'Hey There! Let\'s get AdaptCMS Installed!');
        }

        return parent::render($request, $exception);
    }
}
