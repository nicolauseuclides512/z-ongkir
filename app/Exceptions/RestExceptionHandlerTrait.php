<?php

namespace App\Exceptions;


use App\Cores\Jsonable;
use BadMethodCallException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait RestExceptionHandlerTrait
{

    use Jsonable;

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $e)
    {
        switch (true) {
            case $this->isModelNotFoundException($e):
                $res = $this->modelNotFound();
                break;
            case $this->isNotFoundHttpException($e):
                $res = $this->httpNotFound($e);
                break;

            case $this->isBadMethodCallException($e):
                $res = $this->badRequest();
                break;
            case $this->isSwiftTransport($e):
                $res = $this->swiftTransportException($e);
                break;
            case $this->isAppException($e):
                $res = $this->appException($e);
                break;
            default:
                $res = $this->badRequest($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $res;
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest($message = 'Bad request', $statusCode = Response::HTTP_BAD_REQUEST)
    {
        return $this->jsonResponse([
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => $message
        ], $statusCode);
    }

    /**
     * Returns json response for Eloquent model not found exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function modelNotFound($message = 'Record not found', $statusCode = Response::HTTP_NOT_FOUND)
    {
        return $this->jsonResponse([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => $message
        ], $statusCode);

    }

    protected function httpNotFound(Exception $e)
    {
        return $this->jsonResponse([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Http Not Found'
        ], 404);

    }


    protected function appException($e)
    {
        return $this->jsonResponse([
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'data' => $e->getCause()
        ], $e->getCode() ? $e->getCode() : 500);

    }

    protected function swiftTransportException(Exception $e)
    {
        return $this->jsonResponse([
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $e->getMessage(),
        ], $e->getCode() ? $e->getCode() : 500);

    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload = null, $statusCode = Response::HTTP_NOT_FOUND)
    {
        $payload = $payload ?: [];

        return response()->json($payload, $statusCode);
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isModelNotFoundException(Exception $e)
    {
        return $e instanceof ModelNotFoundException;
    }

    protected function isNotFoundHttpException(Exception $e)
    {
        return $e instanceof NotFoundHttpException;
    }

    protected function isBadMethodCallException(Exception $e)
    {
        return $e instanceof BadMethodCallException;
    }

    protected function isAppException(Exception $e)
    {
        return $e instanceof AppException;
    }

    protected function isSwiftTransport(Exception $e)
    {
        return $e instanceof \Swift_TransportException;
    }

}