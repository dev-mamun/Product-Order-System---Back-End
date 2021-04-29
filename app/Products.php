<?php
/**
 * Created by Md.Abdullah Al Mamun.
 * Email: mamun1214@gmail.com
 * Date: 4/28/2021
 * Time: 1:41 PM
 * Year: 2021
 */

namespace Alisra;

use Alisra\Database;
use PDO;

class Products
{
    public function __construct()
    {
        $db = new Database();
        $this->_db = $db->connect();
    }


    public function create($item)
    {
        $query = "INSERT INTO products (name,sku,description, category, price, image) VALUES  (:name, :sku, :description, :category, :price, :image); ";
        $stmt = $this->_db->prepare($query);
        try {
            return $stmt->execute([
                ':name' => $item[0],
                ':sku' => $item[1],
                ':description' => $item[2],
                ':category' => $item[3],
                ':price' => $item[4],
                ':image' => $item[5]
            ]);
        } catch (\PDOException $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function list()
    {
        $query = "SELECT * FROM products;";
        $stmt = $this->_db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function __distruct()
    {

    }

}