<?php
/**
 * Created by PhpStorm.
 * User: shaneegan
 * Date: 26/10/14
 * Time: 10:07
 */

namespace TC\Controllers;
use Doctrine\DBAL\Connection;


class MessageController {

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

    public function getMessages($locationid)
    {
        $messages = $this->db->fetchAll('SELECT * FROM chatroom WHERE fk_locationid=? ORDER BY timestamp DESC LIMIT 50', array($locationid));
/*
        $output = array();

        foreach($messages as $message)
        {
            $output[] = $messages;
        }
*/
        return $messages;
    }

    public function addMessage($userid, $locationid, $message)
    {

    }
} 