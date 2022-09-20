<?php
include_once "Classes/Response.php";
include_once "Models/Time.php";
include_once "Models/User.php";
use Classes\Request;
use Classes\Response;
use Classes\Auth;

/**
 * Login and get token
 */
Request::post("{$api_prefix}/v1/login", function() {
    $data = Request::getParams();
    $new_token=Auth::getToken($data['email'],$data['password']);
    if ($new_token) {
        $response = new Response('HTTP/1.1 200', 1, array(
            'token'=>$new_token
        ), 'Successfully logged in.', 'JSON');
    } else {
        $response = new Response('HTTP/1.1 400', 0, array(
            'token'=>$new_token
        ), 'Login failed.', 'JSON');
    }
    $response->print();
});

/**
 * Verify token
 */
Request::post("{$api_prefix}/v1/verify", function() {
    $data = Request::getParams();
    $token = $data['token'];
    $token_verified = Auth::verifyToken($token);
    if ($token_verified) {
        $response = new Response('HTTP/1.1 200', 1, array(), 'Successfully logged in.', 'JSON');
    } else {
        $response = new Response('HTTP/1.1 400', 0, array(), 'Login failed.', 'JSON');
    }
    $response->print();
});

/**
 * Get the web pages
 */
Request::post("{$api_prefix}/v1/html", function() {
    $data = Request::getParams();
    $token = Request::getToken();
    $token_verified = Auth::verifyToken($token);
    if ($token_verified) {
        $path = "Pages/{$data['page']}.php";
        if (file_exists($path)) {
            include $path;
            exit;
        } else {
            $response = new Response('HTTP/1.1 404', 0, array(), 'Page not found.', 'JSON');
        }
    } else {
        $response = new Response('HTTP/1.1 400', 0, array(), 'Login failed.', 'JSON');
    }
    $response->print();
});