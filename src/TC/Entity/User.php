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
     * @var int
     */
    protected $id;

    /**
     * User's username aka e-mail address
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
     * @param Device $device
     */
    public function addDevice(Device $device)
    {
        array_push($this->devices, $device);
    }

    /**
     * Set the devices array to a list of devices
     * @param array $devices
     */
    public function setDevices(array $devices)
    {
        $this->devices = $devices;
    }

    /**
     * Get all current devices for this user
     * @return array
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * Set users email address
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get users current email address
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set users ID
     * @param int $id
     */
    private function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get ID for current user
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set password for this user
     * @param string $password
     */
    public function setPassword($password)
    {
        // TODO: hash it and store
        $this->password = $password;
    }

    /**
     * Get password hash for this current user
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

} 