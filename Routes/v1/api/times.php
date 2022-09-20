<?php
include_once "Classes/Request.php";
include_once "Classes/Response.php";
include_once "Classes/Auth.php";
include_once "Classes/File.php";
include_once "Models/Project.php";
include_once "Models/Time.php";

use Automattic\WooCommerce\Admin\Features\OnboardingTasks\Tasks\Tax;
use Classes\Request;
use Classes\Response;
use Classes\Auth;
use Classes\File;
use Models\Project;
use Models\Time;

/**
 * Get time records
 */
Request::get("{$api_prefix}/v1/time", function() {
    $current_user = Auth::getUserByToken(Request::getToken());
    if ($current_user) {
        $time = new Time;
        
        // Get the currnet user's time records
        $filter = array(
            'user_id' => $current_user['id']
        );
        $times = $time->list_time($filter);
        $times = array_merge($times, array('count'=>count($times)));
        if (count($times)) {
            $response = new Response('HTTP/1.1 200', 1, $times, 'Records were found.', 'JSON');
        } else {
            $response = new Response('HTTP/1.1 200', 1, $times, 'No records were found.', 'JSON');
        }
    } else {
        $response = new Response('HTTP/1.1 404', -1, array(), 'User not found.', 'JSON');
    }
    $response->print();
});

/**
 * Get peaks of a day
 */
Request::get("{$api_prefix}/v1/time/{date}/{prject}", function($date,$project_id) {
    $current_user = Auth::getUserByToken(Request::getToken());
    if ($current_user) {
        $time = new Time;
        $peaks = $time->get_peak($date,$project_id);
        $response = new Response('HTTP/1.1 200', 1, $peaks, 'Records were found.', 'JSON');
    } else {
        $response = new Response('HTTP/1.1 404', -1, array(), 'User not found.', 'JSON');
    }
    $response->print();
});

/**
 * Add a Time
 */
Request::post("{$api_prefix}/v1/time", function() {
    $data = Request::getParams();
    $current_user = Auth::getUserByToken(Request::getToken());
    if ($current_user) {

        if (Request::checkParams($data, array('project_id'))) {
            $time = new Time;
            /**
             * To use edit route as starting the timer, we need to check and remove the extra params
             * and set the end time for it. This will prevent the time injection.
             * 
             * But, it is always easier and more safe if we use a new route such as {$api_prefix}/v1/end_timer to stop the timer
             */
            unset($data['start_time'], $data['end_time'], $data['user_id']); // remove the params to make it safe
            $data = array_merge($data,array(
                'user_id'=>$current_user['id'], // set the user_id
                'start_time'=>'now()'           // updates the start_time on server
            ));

            // Add the time record
            $ret = $time->add_time($data);
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
 * Edit a Time
 */
Request::put("{$api_prefix}/v1/time/{id}", function($time_id) {
    $data = Request::getParams();
    $current_user = Auth::getUserByToken(Request::getToken());
    if ($current_user) {

        $user = new Time;
        /**
         * To use edit route as ending the timer, we need to check and remove the extra params
         * and set the end time for it. This will prevent the time injection.
         * 
         * But, it is always easier and more safe if we use a new route such as {$api_prefix}/v1/end_timer to stop the timer
         */
        unset($data['start_time'], $data['end_time'], $data['project_id'], $data['user_id']);
        $data = array(
            'end_time'=>'now()'     // updates the end_time on server
        );

        // Update the time record
        $user->update_time($time_id, $data);
        // Find the time record by id and user_id
        $ret = $user->getBy(array(
            'id'=>$time_id,
            'user_id'=>$current_user['id']
        ));
        $response = new Response('HTTP/1.1 200', 1, $ret, 'User has been updated.', 'JSON');

    } else {
        $response = new Response('HTTP/1.1 404', -1, array(), 'User not found.', 'JSON');
    }
    $response->print();
});

/**
 * Upload File
 */
Request::post("{$api_prefix}/v1/upload", function() {
    $data = Request::getParams();
    $current_user = Auth::getUserByToken(Request::getToken());
    $file = File::upload('sheet','uploads/',null,true,1);   // upload the file
    $rows = $file['rows'];  // get the file's records
    $records = array();
    foreach ($rows as $row) {
        $records[] = array($row[0], $current_user['id'], $row[1], $row[2]); // add the user_id to the array
    }
    $project = new Project();
    $projects_data = array_columns_delete($records,array(2,3,4)); // remove some of columns
    $project->store_data($projects_data);   // add the projects
    $time = new Time();
    $time->store_data($records);    // add the time records

    if ($file) {
        $response = new Response('HTTP/1.1 200', 1, array('size'=>$file['size']), 'File successfully uploaded.', 'JSON');
    } else {
        $response = new Response('HTTP/1.1 400', 1, $file, 'File upload failed.', 'JSON');
    }
    $response->print();
});

/**
 * Delete a Time
 */
Request::delete("{$api_prefix}/v1/time", function() {
    //$data = Request::getParams();
});