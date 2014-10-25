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
 * Class TagFactory
 * @package TC\Controllers
 */
class TagFactory{

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
        $tags = $this->db->fetchArray('SELECT `name` FROM `tag` WHERE `name` LIKE %?%', array($filter));
        return $tags;

    }


}



