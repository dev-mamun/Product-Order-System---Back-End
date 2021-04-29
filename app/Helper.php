<?php
/**
 * Created by Md.Abdullah Al Mamun.
 * Email: mamun1214@gmail.com
 * Date: 4/24/2021
 * Time: 9:17 PM
 * Year: 2021
 */

namespace Alisra;


use DateTimeImmutable;
use Firebase\JWT\JWT;

class Helper
{
    protected static $_secretKey = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
    protected static $_loggedIn;

    public static function response($status = false, $msg = "", $items = [])
    {
        $data = [
            "status" => $status,
            "msg" => $msg,
            "data" => $items,
        ];
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Origin: *');
        header("Content-type: application/json; charset=UTF-8");
        echo json_encode($data);
        exit();
    }

    //This method will validate product inputs
    public static function validateInput()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            return [
                self::filterInput($_POST["name"]),
                self::filterInput($_POST["sku"]),
                self::filterInput($_POST["description"]),
                self::filterInput($_POST["category"]),
                self::filterInput($_POST["price"]),
                self::filterInput($_POST["image"]),
            ];
        } else {
            return false;
        }
    }

    public static function filterInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function getBaseUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    public static function getToken($user)
    {
        $issuedAt = new DateTimeImmutable();
        $expire = $issuedAt->modify('+1 day')->getTimestamp();      // Add 60 seconds
        $serverName = $_SERVER['HTTP_HOST'];

        $data = [
            'iat' => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
            'iss' => $serverName,                       // Issuer
            'nbf' => $issuedAt->getTimestamp(),         // Not before
            'exp' => $expire,                           // Expire
            'user' => $user,                     // User name
        ];

        return JWT::encode($data, self::$_secretKey, 'HS512');
    }

    public static function getRedirectUrl($user)
    {
        if ($user->role_id == 1) {
            return self::getBaseUrl() . "admin/";
        }
        return self::getBaseUrl();
    }

    public static function isLoggedIn()
    {
        $headers = getallheaders();
        if (key_exists("Authorization", $headers)) {
            $token = JWT::decode($headers['Authorization'], self::$_secretKey, array('HS512'));
            if (isset($token->user) && !empty($token->user)) {
                self::$_loggedIn = $token->user;
                return true;
            }

        }
        return false;
    }

    public static function hasPermission()
    {
        if (self::isLoggedIn()) {
            if (self::$_loggedIn->role_id == 1) {
                return true;
            }
        }
        return false;
    }

    public static function isAdmin(){
        if(self::$_loggedIn->role_id == 1){
            return true;
        }
        return false;
    }

}