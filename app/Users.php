<?php
/**
 * Created by Md.Abdullah Al Mamun.
 * Email: mamun1214@gmail.com
 * Date: 4/24/2021
 * Time: 4:50 PM
 * Year: 2021
 */

namespace Alisra;

use Alisra\Database;

class Users
{
    private $_db;

    public function __construct()
    {
        $this->setDB();
    }

    private function setDB()
    {
        $db = new Database();
        $this->_db = $db->connect();

    }

    private function getDB()
    {
        return $this->_db;
    }

    public function getUser($id)
    {

    }

    public function login($username, $password)
    {
        $stmt = $this->getDB()->prepare("SELECT id, full_name, role_id FROM users WHERE username=:username AND password = :password");
        $stmt->execute([
            "username" => $username,
            "password" => $password
        ]);
        return $stmt->fetchObject();
    }

    public function __distruct(){

    }

}