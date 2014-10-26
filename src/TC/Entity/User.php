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
     * @var string
     */
    protected $firstname;

    /**
     * @var string
     */
    protected $lastname;
    /**
     * Password hash (SHA512)
     *
     * @var string
     */
    protected $password;
    /**
     * Users First Name
     *
     * @var string
     */
    protected $firstname;
    /**
     * Users Last Name
     *
     * @var string
     */
    protected $lastname;
    /**
     * Users radius pref for front end
     *
     * @var string
     */
    protected $radiuspref;
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
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
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
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $radiuspref
     */
    public function setRadiuspref($radiuspref)
    {
        $this->radiuspref = $radiuspref;
    }

    /**
     * @return string
     */
    public function getRadiuspref()
    {
        return $this->radiuspref;
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
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
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