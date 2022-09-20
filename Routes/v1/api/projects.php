<?php
include_once "Classes/Request.php";
include_once "Classes/Response.php";
include_once "Models/Project.php";
include_once "Models/User.php";
use Classes\Request;
use Classes\Response;
use Classes\Auth;
use Models\Project;

/**
 * Get list of projects
 */
Request::get("{$api_prefix}/v1/project", function() {
    $current_user = Auth::getUserByToken(Request::getToken());
    if ($current_user) {
        $project = new Project;
        $filter = array(
            'p.user_id' => $current_user['id']
        );
        $projects = $project->list_project($filter);
        foreach ($projects as $k=> $project) {
            if (isset($project['duration'])) {
                $projects[$k]['duration_formated'] = Project::format_time($project['duration']);                
            }
        }
        $projects = array_merge($projects, array('count'=>count($projects)));
        if (count($projects)) {
            $response = new Response('HTTP/1.1 200', 1, $projects, 'Records were found.', 'JSON');
        } else {
            $response = new Response('HTTP/1.1 200', 1, $projects, 'No records were found.', 'JSON');
        }
    } else {
        $response = new Response('HTTP/1.1 404', -1, array(), 'User not found.', 'JSON');
    }
    $response->print();
});

/**
 * Get a Project
 */
Request::get("{$api_prefix}/v1/project/{id}", function($project_id) {
    $current_user = Auth::getUserByToken(Request::getToken());
    if ($current_user) {
        $project = new Project;
        $filter = array(
            'p.id' => $project_id
        );
        $projects = $project->list_project($filter,'id DESC');
        foreach ($projects as $k=> $project) {
            if (isset($project['duration'])) {
                $projects[$k]['duration_formated']=Project::format_time($project['duration']);
            }
        }
        $projects = array_merge($projects, array('count'=>count($projects)));
        if (count($projects)) {
            $response = new Response('HTTP/1.1 200', 1, $projects, 'Records were found.', 'JSON');
        } else {
            $response = new Response('HTTP/1.1 200', 1, $projects, 'No records were found.', 'JSON');
        }
    } else {
        $response = new Response('HTTP/1.1 404', -1, array(), 'User not found.', 'JSON');
    }
    $response->print();
});

/**
 * Add a Project
 */
Request::post("{$api_prefix}/v1/project", function() {
    $data = Request::getParams();
    $current_user = Auth::getUserByToken(Request::getToken());
    if ($current_user) {
        // Check the required params
        if (Request::checkParams($data, array('name'))) {
            $project = new Project;
            $data = array_merge($data,array(
                'user_id'=>$current_user['id'],
                'created'=>'now()',
                'modified'=>'now()'
            ));
            $ret = $project->add_project($data);
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
 * Edit a Project
 */
Request::put("{$api_prefix}/v1/project/{id}", function($project_id) {
    $data = Request::getParams();
    $current_user = Auth::getUserByToken(Request::getToken());
    if ($current_user) {
        $user = new Project;
        $user->update_project($project_id, $data);
        $ret = $user->getBy(array(
            'id'=>$project_id
        ));
        $response = new Response('HTTP/1.1 200', 1, $ret, 'User has been updated.', 'JSON');

    } else {
        $response = new Response('HTTP/1.1 404', -1, array(), 'User not found.', 'JSON');
    }
    $response->print();
});

/**
 * Delete a Project
 */
Request::delete("{$api_prefix}/v1/project", function() {
    //$data = Request::getParams();
});