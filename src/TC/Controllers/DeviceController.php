<?php
/**
 * Created by PhpStorm.
 * User: shaneegan
 * Date: 25/10/14
 * Time: 21:36
 */



namespace TC\Controllers;

use Doctrine\DBAL\Connection;
use TC\Entity\Device;


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
     * @param $filter
     */
    public function getFilterList($filter){
        $tags = $this->db->fetchAll('SELECT * FROM `tag` WHERE `name` LIKE ?', array('%'.$filter.'%'));
        $tagArray = array();
        foreach($tags as $tag){

           $tempTag = new Tag($tag);
           $tagArray[] = $tempTag->toArray();
        }

        return  $tagArray;
    }


}



