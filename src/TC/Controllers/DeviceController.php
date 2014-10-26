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


}



