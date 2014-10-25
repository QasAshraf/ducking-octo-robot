<?php
/**
 * Created by PhpStorm.
 * User: qasim
 * Date: 25/10/14
 * Time: 19:07
 */

namespace TC\Entity;


/**
 * Class Tag
 * @package TC\Entity
 */
class Tag {
    /**
     * Internal ID of this tag.
     *
     * @var int
     */
    protected $id = null;

    /**
     * Name of this tag.
     *
     * @var string
     */
    protected $name;

    /**
     * Default constructor, pass in keys in array and they will get assigned.
     *
     * @param array $data
     */
    function __construct($data)
    {
        foreach($data as $key => $value)
        {
            $this->$key = $value;
        }
    }

    /**
     * Get the internal ID of this Tag object
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the name of this Tag
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of this Tag
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Produce array representation of this object. Optionally display the ID if required, defaults to off.
     *
     * @param bool $displayId
     *
     * @return array
     */
    public function toArray($displayId = false)
    {
        $array = array(
          'name' => $this->getName()
        );

        if($displayId)
        {
            $array['id'] = $this->getId();
        }

        return $array;
    }
} 