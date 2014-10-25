<?php
/**
 * Created by PhpStorm.
 * User: qasim
 * Date: 25/10/14
 * Time: 19:07
 */

namespace TC\Entity;

/**
 * Class Device
 * @package TC\Entity
 */
class Device {

    /**
     * Device unique ID
     *
     * @var int null
     */
    protected $id = null;

    /**
     * Device's API key
     *
     * @var string null
     */
    protected $api_key;

    /**
     * User this device belongs to
     *
     * @var User $user
     */
    protected $user;

    /**
     * Last known location of this device
     *
     * @var Location $location
     */
    protected $location;

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
     * Update location with a TC\Entity\Location object
     *
     * @param Location $location
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;
    }

    /**
     * Get current TC\Entity\Location for this device
     *
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set the TC\Entity\User for this device
     *
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the current user object this device is tied to.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Get the current internal ID of this object.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set this device's API key
     *
     * @param null $api_key
     */
    public function setApiKey($api_key)
    {
        // TODO: Generate this based on timestamp and user's email
        $this->api_key = $api_key;
    }

    /**
     * Get this device\s API key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
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
          'api_key' => $this->getApiKey(),
          'user' => $this->getUser(),
          'location' => $this->getLocation()
        );

        if($displayId)
        {
            $array['id'] = $this->getId();
        }

        return $array;
    }

} 