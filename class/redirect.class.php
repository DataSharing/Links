<?php

class Redirect extends Controller
{
    public static function check($get)
    {
        global $DB;

        $requete = $DB->prepare('select id,redirect_to from shortcuts where shortcut="' . $get . '"');
        $requete->execute();
        if ($requete->rowCount() == 1) {
            $row = $requete->fetch();
            self::redirect_to($row['redirect_to'], $row['id']);
        }
        return false;
    }

    public static function redirect_to($url, $id_url)
    {
        global $DB;
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'noip';
        $api = self::apiCountry($ip);
        $country = isset($api->country) ? $api->country : 'NA';
        $regionName = isset($api->regionName) ? $api->regionName : 'NA';
        $city = isset($api->city) ? $api->city : 'NA';
        $zip = isset($api->zip) ? $api->zip : 'NA';
        $requete = $DB->prepare('INSERT INTO stats(id_url,ip,date_redirect,country,regionName,city,zip) values("' . $id_url . '","' . $ip . '","' . date('Y-m-d H:i:s') . '","'.$country.'","'.$regionName.'","'.$city.'","'.$zip.'")');
        $requete->execute();

        header('location:' . $url);
    }

    public static function apiCountry($ip)
    {
        $url = "http://ip-api.com/json/".$ip;
        $response = file_get_contents($url);
        $data = json_decode($response);

        return $data;
    }
}
