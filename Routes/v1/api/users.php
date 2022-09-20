<?php
include_once "Classes/Response.php";
include_once "Models/Time.php";
include_once "Models/User.php";
use Classes\Request;
use Classes\Response;
use Classes\Auth;
use Classes\Entity;
use Models\User;

/**
 * Get a User
 */
Request::get("{$api_prefix}/v1/user", function() {
    $current_user = Auth::getUserByToken(Request::getToken());
    if ($current_user) {
        $response = new Response('HTTP/1.1 200', 1, $current_user, 'User found.', 'JSON');
    } else {
        $response = new Response('HTTP/1.1 404', -1, array(), 'User not found.', 'JSON');
    }
    $response->print();
});

/**
 * Add a User
 */
Request::post("{$api_prefix}/v1/user", function() {
    $data = Request::getParams();
    $current_user = Auth::getUserByToken(Request::getToken());
    if ($current_user) {

        if (Request::checkParams($data, array('email','first_name','last_name','password'))) {
            $user = new User;
            $data = array_merge($data,array(
                'created'=>'now()',
                'modified'=>'now()'
            ));
            $user_id = $user->add_user($data);
            $ret = $user->getBy(array(
                'id'=>$user_id['id']
            ));
            $response = new Response('HTTP/1.1 200', 1, $ret, 'User found.', 'JSON');
        } else {
            $response = new Response('HTTP/1.1 400', -1, array(), 'Bad request.', 'JSON');
        }

    } else {
        $response = new Response('HTTP/1.1 404', -1, array(), 'User not found.', 'JSON');
    }
    $response->print();
});

/**
 * Edit a User
 */
Request::put("{$api_prefix}/v1/user", function() {
    $data = Request::getParams();
    $current_user = Auth::getUserByToken(Request::getToken());
    if ($current_user) {
        $user = new User;
        $data = array_merge($data,array(
            'modified'=>'now()'
        ));
        $user->update_user($current_user['id'],$data);
        $ret = $user->getBy(array(
            'id'=>$current_user['id']
        ));
        $response = new Response('HTTP/1.1 200', 1, $ret, 'User has been updated.', 'JSON');

    } else {
        $response = new Response('HTTP/1.1 404', -1, array(), 'User not found.', 'JSON');
    }
    $response->print();
});

/**
 * Logout User
 */
Request::get("{$api_prefix}/v1/logout", function() {
    $data = Request::getParams();
    $current_user = Auth::getUserByToken(Request::getToken());
    if ($current_user) {
        $user = new User;
        $data = array_merge($data,array(
            'active_token'=>''
        ));
        $user->update_user($current_user['id'],$data);
        $response = new Response('HTTP/1.1 200', 1, array(), 'User has been logged out.', 'JSON');
    } else {
        $response = new Response('HTTP/1.1 404', -1, array(), 'User not found.', 'JSON');
    }
    $response->print();
});

/**
 * Delete a User
 */
Request::delete("{$api_prefix}/v1/user", function() {
    //$data = Request::getParams();
});