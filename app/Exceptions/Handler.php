<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // تحقق مما إذا كان الطلب من نوع GET ويحاول الوصول إلى route يتطلب POST
        if ($request->isMethod('get') && $this->isPostRoute($request)) {
            return response()->json([
                'message' => 'Unauthorized access to this route.'
            ], 403);
        }

        return parent::render($request, $exception);
    }

    /**
     * دالة للتحقق من أن الراوت يتطلب POST
     */
    protected function isPostRoute($request)
    {
        // اضافة اسماء الروابط الخاصة بالـ POST هنا
        $postRoutes = [
            'post-route-name', // قم باستبدالها بأسماء الروابط الخاصة بك
            'another-post-route-name'
        ];

        return in_array($request->route()->getName(), $postRoutes);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
