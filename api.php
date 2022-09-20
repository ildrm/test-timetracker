<?php
require_once "db_config.php";
include_once "app_config.php";
include_once "functions.php";

include_once "Classes/Auth.php";
include_once "Classes/Request.php";
include_once "Classes/Response.php";
include_once "Models/Time.php";
include_once "Models/User.php";
use Classes\Request;
use Classes\Response;

/**
 * Enable CORS
 */
Response::cors();

/**
 * Test Request
 */
Request::get("{$api_prefix}/v1/test", function() {
    $data = Request::getParams();
    $response = new Response('HTTP/1.1 200', 1, $data, '', 'JSON');
    $response->print();
});

/**
 * API v1
 */
include_once "Routes/v1/api/index.php";

$response = new Response('HTTP/1.1 404', -1, array(), 'Request Not found', 'JSON');
$response->print();