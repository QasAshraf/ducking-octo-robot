<?php
/**
 * Created by PhpStorm.
 * User: shaneegan
 * Date: 25/10/14
 * Time: 21:36
 */



namespace TC\Controllers;

use Doctrine\DBAL\Connection;
use TC\Entity\Tag;


/**
 * Class TagController
 * @package TC\Controllers
 */
class TagController{

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
    public function findAll()
    {
        $result = $this->db->fetchAll('SELECT idtag AS id, name FROM `tag`');
        $tagArray = array();
        foreach($result as $tag)
        {
            $tempTag = new Tag($tag);
            $tagArray[] = $tempTag->toArray(true);
        }

        return $tagArray;
    }

    /**
     * Filter the tags in the DB, find only those that match the supplied filter.
     *
     * @param $filter
     *
     * @return array
     */
    public function getFilterList($filter){
        $tags = $this->db->fetchAll('SELECT idtag AS id, name FROM `tag` WHERE `name` LIKE ?', array('%'.$filter.'%'));
        $tagArray = array();
        foreach($tags as $tag){

           $tempTag = new Tag($tag);
           $tagArray[] = $tempTag->toArray(true);
        }

        return  $tagArray;
    }


}



