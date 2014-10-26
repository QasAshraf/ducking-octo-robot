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


        $messages = $this->db->fetchAll('SELECT * FROM chatroom LEFT JOIN device ON  device.iddevice = chatroom.fk_deviceid LEFT JOIN user ON user.iduser = device.fk_iduser WHERE fk_locationid=? ORDER BY timestamp DESC LIMIT 50', array($locationid));
/*
        $output = array();

        foreach($messages as $message)
        {
            $output[] = $messages;
        }
*/
        return $messages;
    }

    public function addMessage($api_key, $message)
    {

        $deviceInfo = $this->db->fetchAssoc('SELECT * FROM device LEFT JOIN userlocation ON device.iddevice = userlocation.fk_deviceid WHERE api_key = ?', array($api_key));
        $deviceid = $deviceInfo['iddevice'];
        $locationid = $deviceInfo['fk_locationid'];
        $ts = time();

        $this->db->executeQuery('INSERT INTO chatroom (fk_deviceid, fk_locationid, timestamp, message) values (?,?,?,?)',array($deviceid, $locationid, $ts, $message));



    }
} 