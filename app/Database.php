<?php
/**
 * Created by Md.Abdullah Al Mamun.
 * Email: mamun1214@gmail.com
 * Date: 4/25/2021
 * Time: 3:04 PM
 * Year: 2021
 */

namespace Alisra;


use PDO;

class Database
{
    /**
     * PDO instance
     * @var type
     */
    private $pdo;
    /**
     * path to the sqlite file
     */
    private $_db;


    public function __construct()
    {
        $this->_db = dirname(__DIR__) . "/db.sqlite3";
    }

    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */

    public function connect()
    {
        if ($this->pdo == null) {
            $this->pdo = new PDO("sqlite:" . $this->_db);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($this->pdo) {
                return $this->pdo;
            } else {
                throw new \Exception("Could not connect to SQLite database.");
            }
        }
        return $this->pdo;
    }

}