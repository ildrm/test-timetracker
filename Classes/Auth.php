<?php
namespace Classes;

include_once 'Entity.php';
use Classes\Entity;

/**
 * Class Auth
 */
class Auth {
    /**
     * Verify the token
     * @param string $token
     * @return bool
     */
    public static function verifyToken($token)
    {
        $user = new Entity('users');
        $find_user = $user->getBy(array(
            'active_token'=>$token
        ));
        return $find_user ? true : false;
    }

    /**
     * Send email and password to get the token
     * @param string $email
     * @param string $password
     * @return string
     */
    public static function getToken($email, $password)
    {
        $user = new Entity('users');
        $find_user = $user->getBy(array(
            'email'=>$email,
            'password'=>sha1($password)
        ));
        if ($find_user) {
            $new_token = sha1(date("YmdHis") . $find_user['id']);
            $user->update($find_user['id'],array(
                'active_token'=>$new_token,
                'last_login'=>'now()'
            ));
        }
        return $new_token ?? '';
    }

    /**
     * Find and get the user by token
     * @param string $token
     * @return array
     */
    public static function getUserByToken($token)
    {
        $user = new Entity('users');
        return $user->getBy(array(
            'active_token'=>$token
        ));
    }
}