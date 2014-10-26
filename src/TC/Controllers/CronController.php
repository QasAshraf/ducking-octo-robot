<?php
/**
 * Created by PhpStorm.
 * User: shaneegan
 * Date: 25/10/14
 * Time: 23:36
 */



namespace TC\Controllers;

use Doctrine\DBAL\Connection;
use TC\Entity\Tag;


/**
 * Class CronController
 * @package TC\Controllers
 */
class CronController{

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
     * Get all the tags from the DB
     *
     * @return array
     */
    public function recreateLocations()
    {
        $result = $this->db->fetchAll('SELECT * FROM `device`');
        $locArray = array();
        foreach($result as $tag)
        {
            $tempTag = new Tag($tag);
            $tagArray[] = $tempTag->toArray();
        }

        return $tagArray;
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



