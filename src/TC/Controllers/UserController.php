<?php
/**
 * Created by PhpStorm.
 * User: shaneegan
 * Date: 25/10/14
 * Time: 21:36
 */



namespace TC\Controllers;

use Doctrine\DBAL\Connection;
use TC\Entity\User;


/**
 * Class UserController
 * @package TC\Controllers
 */
class UserController{

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Get the user object from DB
     *
     * @param $email
     *
     * @return User
     */
    public function find($email)
    {
        $result = $this->db->fetchAll('SELECT iduser AS id, firstname, lastname, password FROM `user` WHERE email = ?', array($email));
        foreach($result as $user)
        {
            return new User($user);
        }
        return array();
    }

    public function create($user)
    {
        $result = $this->db->fetchAll('SELECT * FROM `user` WHERE email = ?', array($user['email']));
        if(!empty($result)) {
            throw new \Exception("Email already exists");
        }
        $this->db->executeQuery('INSERT INTO `user` (email, firstname, lastname, password) VALUES (?,?,?,?)',
          array($user['email'], $user['firstname'], $user['lastname'], $user['password']));
        return $this->db->lastInsertId();

    }

    public function update($user)
    {
        $result = $this->db->fetchAll('SELECT * FROM `user` WHERE email = ?', array($user['email']));
        if(empty($result)) {
            throw new \Exception("User not found from email address");
        }
        $this->db->executeQuery('UPDATE `user` set firstname = ?, lastname = ?  where email = ?', array($user['firstname'], $user['lastname'], $user['email']));
        return $this->db->lastInsertId();

    }

    /**
     * Get rid of user
     *
     * @param $uid
     */
    public function delete($uid)
    {
        $this->db->delete('device', array('fk_iduser' > $uid));
        $this->db->delete('userlocation', array('fk_iduser' > $uid));
        $this->db->delete('usertag', array('fk_iduser' > $uid));
        $this->db->delete('user', array('iduser' => $uid));
    }
}



