<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    protected $exceptionMap = [
        ModelNotFoundException::class => [
            'code' => 404,
            'message' => 'Could not find what you were looking for.',
            'adaptMessage' => false,
        ],

        NotFoundHttpException::class => [
            'code' => 404,
            'message' => 'Could not find what you were looking for.',
            'adaptMessage' => false,
        ],

        MethodNotAllowedHttpException::class => [
            'code' => 405,
            'message' => 'This method is not allowed for this endpoint.',
            'adaptMessage' => false,
        ],

        ValidationException::class => [
            'code' => 422,
            'message' => 'Some data failed validation in the request',
            'adaptMessage' => false,
        ],

        \InvalidArgumentException::class => [
            'code' => 400,
            'message' => 'You provided some invalid input value',
            'adaptMessage' => true,
        ],
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $response = $this->formatException($exception);

        return response()->json(
            [
                "message" => $response["title"],
                "success" => false,
                "data" => $response["description"]
            ],
            $response['status'] ?? 500
        );
    }

    protected function formatException(\Throwable $exception): array
    {
        # We get the class name for the exception that was raised
        $exceptionClass = get_class($exception);

        # we see if we have registered it in the mapping - if it isn't
        # we create an initial structure as an 'Internal Server Error'
        # note that this can always be revised at a later time
        $definition = $this->exceptionMap[$exceptionClass] ?? [
            'code' => 500,
            'message' => $exception->getMessage() ?? 'Something went wrong while processing your request',
            'adaptMessage' => false,
        ];

        if (!empty($definition['adaptMessage'])) {
            $definition['message'] = $exception->getMessage() ?? $definition['message'];
        }

        return [
            'status' => $definition['code'] ?? 500,
            'title' => $definition['title'] ?? 'Error',
            'description' => $definition['message'],
        ];
    }
}
