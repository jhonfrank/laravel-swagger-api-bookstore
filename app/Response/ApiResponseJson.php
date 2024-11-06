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
    const HTTP_UNPROCESSABLE_CONTENT = 422;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    private $statusCode;
    private $data;
    private $message;

    public function __construct($statusCode, $data = null, $message = '')
    {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->message = $message;
    }

    public function send()
    {
        $response = [
            'data' => $this->data
        ];

        if (!empty($this->message)) {
            $response['message'] = $this->message;
        }

        return response()->json($response, $this->statusCode);
    }

    public static function ok($data)
    {
        return new self(self::HTTP_OK, $data);
    }

    public static function created($data)
    {
        return new self(self::HTTP_CREATED, $data, 'Resource created successfully.');
    }

    public static function noContent()
    {
        return new self(self::HTTP_NO_CONTENT);
    }

    public static function badRequest($message = 'Bad request.')
    {
        return new self(self::HTTP_BAD_REQUEST, null, $message);
    }

    public static function unauthorized($message = 'Unauthorized.')
    {
        return new self(self::HTTP_UNAUTHORIZED, null, $message);
    }

    public static function forbidden($message = 'Forbidden.')
    {
        return new self(self::HTTP_FORBIDDEN, null, $message);
    }

    public static function notFound($message = 'Resource not found.')
    {
        return new self(self::HTTP_NOT_FOUND, null, $message);
    }

    public static function unprocessableContent($data, $message = 'Request is incorrect format.')
    {
        return new self(self::HTTP_UNPROCESSABLE_CONTENT, $data, $message);
    }

    public static function internalServerError($message = 'Internal server error.')
    {
        return new self(self::HTTP_INTERNAL_SERVER_ERROR, null, $message);
    }

}
