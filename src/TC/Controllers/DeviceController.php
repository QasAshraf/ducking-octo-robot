<?php
/**
 * Created by PhpStorm.
 * User: shaneegan
 * Date: 25/10/14
 * Time: 21:36
 */



namespace TC\Controllers;

use Doctrine\DBAL\Connection;

/**
 * Class DeviceController
 * @package TC\Controllers
 */
class DeviceController{

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
     * Checks for the existence of a device identified by supplied $key.
     *
     * @param $key
     *
     * @return bool
     */
    public function exists($key)
    {
        return 1 === (int) $this->db->fetchColumn('SELECT count(*) FROM device WHERE api_key = ?', array($key));
    }
    /**
     * Removes the device identified by the provided API key from the database
     *
     * @param $key
     */
    public function remove($key)
    {
        $this->db->delete('device', array('api_key' => $key));
    }

    /**
     * Takes a user's ID, generates an API key based on this, stores in DB and returns API key to user.
     *
     * @param $uid
     *
     * @return string
     */
    public function create($uid)
    {
        $seed = rand() + time();
        $key = hash('sha512', $seed);

        $data = array(
            'user_id' => $uid,
            'api_key' => $key
        );

        $this->db->insert('device', $data);
        return $key;
    }

    /**
     * Takes device api key and update the lat, lon
     *
     * @param $key
     *
     * @param $lat
     *
     * @param $lon
     */
    public function updateLocation($key, $lat, $lon)
    {
        $result = $this->db->fetchAll('SELECT * FROM `device` WHERE api_key = ?', array($key));
        if(empty($result)) {
            throw new \Exception("API key not found");
        }
        $this->db->executeQuery('UPDATE `device` set lat = ?, lon = ?  where api_key = ?', array($lat, $lon, $key));
        return $this->db->lastInsertId();

    }

}



