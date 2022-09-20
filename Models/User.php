<?php
namespace Models;

include_once "Classes/Entity.php";
use Classes\Entity;

/**
 * User Model
 */
class User extends Entity {
    private $user;

    function __construct()
    {
        parent::__construct('users');       // inits the Entity
        $this->user = new Entity('users');  // new instance of Entity
    }

    /**
     * Filter users
     * @param array $filters
     * @return array|false
     */
    function list_user($filters)
    {
        return $this->user->list($filters);
    }

    /**
     * Add a user
     * @param array $data
     * @return array|false
     */
    function add_user($data)
    {
        $user_id = parent::add($data);
        return $this->user->getBy(array(
            'id'=>$user_id
        ));
    }

    /**
     * Update a user
     * @param integer $id
     * @param array $data
     * @return array|false
     */
    function update_user($id, $data)
    {
        parent::update($id, $data);
        return $this->user->getBy(array(
            'id'=>$id
        ));
    }
}