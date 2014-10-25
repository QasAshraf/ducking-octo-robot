<?php
/**
 * Created by PhpStorm.
 * User: qasim
 * Date: 25/10/14
 * Time: 19:07
 */

namespace TC\Entity;


/**
 * Class Location
 * @package TC\Entity
 */
class Location {

    /**
     * Internal ID of this location object.
     *
     * @var int
     */
    protected $id = null;

    /**
     * Name of this location, duuh.
     *
     * @var string
     */
    protected $name;

    /**
     * Latitude of this location, negative means south and positive means north of the equator.
     *
     * @var float
     */
    protected $latitude;

    /**
     * Longitude of this location, negative means west and positive means east.
     *
     * @var float
     */
    protected $longitude;

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
     * Get the internal ID used to represent this object.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the latitude of this location
     *
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        // TODO: Add validator, can only be -90 <= 0 <= 90
        $this->latitude = $latitude;
    }

    /**
     * Get the latitude of this location
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set the longitude of this location
     *
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        // TODO: Add validator, can only be -180 <= 0 <= 180
        $this->longitude = $longitude;
    }

    /**
     * Get the current longitude of this location.
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set the name of this location eg. Manchester
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the current name of this location
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
          'name' => $this->getName(),
          'lat' => $this->getLatitude(),
          'lon' => $this->getLongitude()
        );

        if($displayId)
        {
            $array['id'] = $this->getId();
        }

        return $array;
    }

} 