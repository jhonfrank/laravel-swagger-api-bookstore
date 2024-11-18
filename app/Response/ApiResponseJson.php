<?php

namespace App\Response;

class ApiResponseJson
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    private $status;
    private $data;
    private $message;
    private $success;

    public static function make($response, $status)
    {
        return response()->json($response, $status);
    }

    public static function success($status, $data = null, $message = null)
    {
        $response = [
            'success' => true
        ];

        if (!is_null($message)) {
            $response['message'] = $message;
        }

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return self::make($response, $status);
    }

    public static function error($status, $message = null, $errors = null)
    {
        $response = [
            'success' => false
        ];

        if (!is_null($message)) {
            $response['message'] = $message;
        }

        $error = null;

        if (!is_array($errors)) {
            $error = $errors;
            $errors = null;
        }

        if (!is_null($error)) {
            $response['error'] = $error;
        }

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return self::make($response, $status);
    }

    public static function ok($data, $message = null)
    {
        return self::success(self::HTTP_OK, $data, $message);
    }

    public static function created($data, $message = 'Resource created successfully.')
    {
        return self::success(self::HTTP_CREATED, $data, $message);
    }

    public static function noContent($message = null)
    {
        return self::success(self::HTTP_NO_CONTENT, message: $message);
    }

    public static function badRequest($message = 'Bad request.')
    {
        return self::error(self::HTTP_BAD_REQUEST, $message);
    }

    public static function unauthorized($message = 'Unauthorized.')
    {
        return self::error(self::HTTP_UNAUTHORIZED, $message);
    }

    public static function forbidden($message = 'Forbidden.')
    {
        return self::error(self::HTTP_FORBIDDEN, $message);
    }

    public static function notFound($message = 'Resource not found.')
    {
        return self::error(self::HTTP_NOT_FOUND, $message);
    }

    public static function unprocessableEntity($errors, $message = 'Request is incorrect format.')
    {
        return self::error(self::HTTP_UNPROCESSABLE_ENTITY, $message, $errors);
    }

    public static function internalServerError($message = 'Internal server error.')
    {
        return self::error(self::HTTP_INTERNAL_SERVER_ERROR, $message);
    }

}
