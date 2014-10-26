<?php
/**
 * Created by PhpStorm.
 * User: shaneegan
 * Date: 25/10/14
 * Time: 21:36
 */



namespace TC\Controllers;

use Doctrine\DBAL\Connection;

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
     * Checks for the existence of a device identified by supplied $key.
     *
     * @param $key
     *
     * @return bool
     */
    public function exists($key)
    {
        return 1 === (int) $this->db->fetchColumn('SELECT count(*) FROM device WHERE api_key = ?', array($key));
    }
    /**
     * Removes the device identified by the provided API key from the database
     *
     * @param $key
     */
    public function remove($key)
    {
        $this->db->delete('device', array('api_key' => $key));
    }

    /**
     * Takes a User array, generates an API key based on this, stores in DB and returns API key to user.
     *
     * @param $user
     *
     * @return string
     */
    public function create($user)
    {
        $seed = rand() + time();
        $key = substr(hash('sha256', $seed), 0, 40);

        $data = array(
            'fk_iduser' => $user['id'],
            'api_key' => $key,
            'lat' => $user['latitude'],
            'lon' => $user['longitude'],
        );

        $this->db->insert('device', $data);
        //$this->updateDeviceLocation($key, $user['latitude'], $user['longitude']);

        return $key;
    }3


    public static function vincentyGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad(floatval($latitudeFrom));
        $lonFrom = deg2rad(floatval($longitudeFrom));
        $latTo = deg2rad(floatval($latitudeTo));
        $lonTo = deg2rad(floatval($longitudeTo));

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }

    private function shouldJoinLocation($locs, $tags){
        $joinRadius = 2; //2km

        $distance = $this->vincentyGreatCircleDistance($locs['lat1'], $locs['lon1'], $locs['lat2'], $locs['lon2']);

        echo "Distance: $distance";

        if($distance <= $joinRadius)
        {
            echo "we are close enough";
            //we are close enough
            //Check if the tags 2 tags match
            if($tags['user1'] == $tags['loc1'] || $tags['user1'] == $tags['loc2']){
                echo "we have matched 1st tag";
                //First tag matched a locations tag
                if($tags['user2'] == $tags['loc1'] || $tags['user2'] == $tags['loc2']){
                    echo "we have matched 2nd tag";
                    return true;
                }
            }
        }

        return false;

    }

    private function addLocation($lat, $lon, $tag1_id, $tag2_id)
    {
        $data = array(
            "lat" => $lat,
            "lon" => $lon,
            "tag1" => $tag1_id,
            "tag2" => $tag2_id
        );

        $this->db->insert('location', $data);
        return $this->db->lastInsertId();
    }

    private function joinLocation($locationId, $userId){

        $this->db->executeQuery('INSERT INTO userlocation (fk_deviceid, fk_locationid) VALUES (?,?) ON DUPLICATE KEY UPDATE fk_locationid=? ', array($userId, $locationId, $locationId));
        echo "Joining Location";
    }

    private function updateLocations($key, $lat, $lon){

        //assuming API key is here, should be safe as checked previously
        $deviceRow = $this->db->fetchAssoc('SELECT * FROM `device` WHERE api_key = ?', array($key));
        print_r($deviceRow);

        $fk_iduser = $deviceRow['fk_iduser'];

        $userTags = $this->db->fetchAll('SELECT * FROM usertag where fk_userid = ?', array($fk_iduser)); //TODO: order by priority
        echo "MyTags:";
        print_r($userTags);
        $tagCount = count($userTags);

        echo("Tag Count: " . $tagCount);

        $tagCount--;

        if($tagCount < 1)
            return "not enough tags";

        $groups = $this->db->fetchAll('SELECT * FROM `location`');

        $foundLocation = false;

        $tagindex1 = 0;
        $tagindex2 = $tagindex1 + 1;

        while($tagindex2 <= $tagCount)
        {

            foreach($groups as $group ){
                $locs = array(
                    "lat1" => $deviceRow['lat'],
                    "lon1" => $deviceRow['lon'],
                    "lat2" => $group['lat'],
                    "lon2" => $group['lon']
                );

                $tags = array(
                    "user1" => $userTags[$tagindex1]['fk_tagid'],
                    "user2" => $userTags[$tagindex2]['fk_tagid'],
                    "loc1" => $group['tag1'],
                    "loc2" => $group['tag2']
                );

                if($this->shouldJoinLocation($locs, $tags)){
                    $this->joinLocation($group['idlocation'], $fk_iduser);
                    echo "WUP WUP we have to join";
                    $foundLocation = true;
                    break;
                }



            }
            if($foundLocation)
                break;

            $tagindex1 += 1;
            $tagindex2 += 1;
            //we have finished
            if($tagindex2 > $tagCount)
                break;
        }

        if(!$foundLocation){
            echo "we didnt find a location - create one";
            $locId = $this->addLocation($deviceRow['lat'],$deviceRow['lon'], $userTags[0]['fk_tagid'], $userTags[1]['fk_tagid']);
            $this->joinLocation($locId, $fk_iduser);
        }


    }



    /**
     * Takes device api key and update the lat, lon
     *
     * @param $key
     *
     * @param $lat
     *
     * @param $lon
     */
    public function updateDeviceLocation($key, $lat, $lon)
    {
        $result = $this->db->fetchAll('SELECT * FROM `device` WHERE api_key = ?', array($key));
        if(empty($result)) {
            throw new \Exception("API key not found");
        }
        $this->db->executeQuery('UPDATE `device` set lat = ?, lon = ?  where api_key = ?', array($lat, $lon, $key));

        $this->updateLocations($key, $lat, $lon);
        return $this->db->lastInsertId();

    }

    public function getUserIdFromKey($key)
    {
        return $this->db->fetchColumn('SELECT fk_iduser FROM device WHERE api_key = ?', array($key));
    }

}



