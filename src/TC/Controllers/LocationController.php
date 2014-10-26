<?php
/**
 * Created by PhpStorm.
 * User: shaneegan
 * Date: 25/10/14
 * Time: 21:36
 */



namespace TC\Controllers;

use Doctrine\DBAL\Connection;
use TC\Entity\Location;


/**
 * Class LocationController
 * @package TC\Controllers
 */
class LocationController{

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
     * Get all the locations from the DB
     *
     * @return array
     */
    public function findAll()
    {
        $result = $this->db->fetchAll('SELECT idlocation AS id, name, lat AS latitude, lon AS longitude, tag1, tag2 FROM location');
        $locationArray = array();
        foreach($result as $location)
        {
            $location['tag1'] = $this->db->fetchColumn('SELECT name FROM tag WHERE idtag = ?',
            array($location['tag1']));
            $location['tag2'] = $this->db->fetchColumn('SELECT name FROM tag WHERE idtag = ?',
            array($location['tag2']));
            $countResult = $this->db->executeQuery('SELECT COUNT(*) FROM userlocation WHERE fk_locationid = ?', array($location['id']));
            $location['count'] =  $countResult->rowCount();
            $tempLocation = new Location($location);
            $locationArray[] = $tempLocation->toArray(true);
        }

        return $locationArray;
    }

}



