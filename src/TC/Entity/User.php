<?php
/**
 * Created by PhpStorm.
 * User: qasim
 * Date: 25/10/14
 * Time: 19:07
 */

namespace TC\Entity;

/**
 * Class User
 * @package TC\Entity
 */
class User {

    /**
     * User's unique ID
     *
     * @var int
     */
    protected $id = null;

    /**
     * User's username aka e-mail address
     *
     * @var string
     */
    protected $email;
    /**
     * Password hash (SHA512)
     *
     * @var string
     */
    protected $password;
    /**
     * Array of Devices
     *
     * @var array
     */
    protected $devices = array();

    /**
     * Array of Tags
     * @var array
     */
    protected $tags = array();

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
     * Add a device to this users list of devices
     *
     * @param Device $device
     */
    public function addDevice(Device $device)
    {
        array_push($this->devices, $device);
    }

    /**
     * Set the devices array to a list of devices
     *
     * @param array $devices
     */
    public function setDevices(array $devices)
    {
        $this->devices = $devices;
    }

    /**
     * Get all current devices for this user
     *
     * @return array
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * Set users email address
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get users current email address
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set users ID
     *
     * @param int $id
     */
    private function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get ID for current user
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set password for this user
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        // TODO: hash it and store
        $this->password = $password;
    }

    /**
     * Get password hash for this current user
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Add a specific tag to this users list of tags
     *
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        array_push($this->tags, $tag);
    }

    /**
     * Set the tags for this user, pass in a complete array of TC\Entity\Tag objects.
     *
     * @param array $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Get list of tags this user is interested in.
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
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
          'email' => $this->getEmail(),
          'devices' => $this->getDevices(),
          'tags' => $this->getTags()
        );

        if($displayId)
        {
            $array['id'] = $this->getId();
        }

        return $array;
    }

} 