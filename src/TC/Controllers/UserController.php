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

    public function findById($id)
    {
        $result = $this->db->fetchAll('SELECT iduser AS id, firstname, lastname, password, email FROM `user` WHERE iduser = ?',
          array($id));

        $devices = $this->db->fetchAll('SELECT friendly_name, api_key FROM `device` WHERE fk_iduser = ?',
          array($id));

        $tags = $this->db->fetchAll('SELECT tag.idtag AS id, tag.name FROM tag JOIN usertag ON tag.idtag = usertag.fk_tagid WHERE usertag.fk_userid = ?',
          array($id));

        foreach($result as $user)
        {
            $user['devices'] = $devices;
            $user['tags'] = $tags;
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
        $this->db->executeQuery('UPDATE `user` set user.firstname = ?, user.lastname = ? WHERE user.iduser = (SELECT
        fk_iduser FROM device WHERE device.api_key = ?)', array($user['firstname'], $user['lastname'],
            $user['api_key']));
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

    public function deleteAllTags($uid)
    {
        $this->db->delete('usertag', array('fk_iduser' > $uid));
    }

    public function bindTagToUser($uid, $tagid)
    {
        $data = array(
          'fk_userid' => $uid,
          'fk_tagid' => $tagid
        );

        $this->db->insert('usertag', $data);
    }
}



