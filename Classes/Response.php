<?php
namespace Classes;

/**
 * Class Response
 * This class is used to handle the respones
 */
class Response {
    private $response;

    /**
     * Sets the header for CORS
     * @return bool
     */
    public static function cors()
    {
        try {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json; charset=UTF-8");
            header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE"); // enabled methods
            header("Access-Control-Max-Age: 3600");
            header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    function __construct($http_status, $status, $data, $message, $format)
    {
        $this->formatResponse($http_status, $status, $data, $message, $format);
    }

    /**
     * Set a default format for the JSON responses
     * @param string $http_status
     * @param mixed $status
     * @param mixed $data
     * @param string $message
     * @return void
     */
    public function formatJson($http_status, $status, $data, $message)
    {
        $this->response = array(
            'http_status'=>"$http_status $message",
            'status'=>$status,
            'data'=>$data,
            'message'=>$message,
            'data_hash'=>sha1(json_encode($data)),  // can be used for caching the data
        );
    }

    /**
     * Set a default format for the responses
     * @param string $http_status
     * @param mixed $status
     * @param mixed $data
     * @param string $message
     * @param string $format
     * @return void
     */
    public function formatResponse($http_status, $status, $data, $message, $format)
    {
        switch (strtoupper($format)) {
            case 'JSON':
                $this->formatJson($http_status, $status, $data, $message);
                break;
        }
    }

    /**
     * Show the response
     * @return void
     */
    public function print()
    {
        header($this->response['http_status']);
        header('Content-Type: application/json; charset=utf-8');
        unset($this->response['http_status']);
        echo json_encode($this->response);
        exit;
    }
}