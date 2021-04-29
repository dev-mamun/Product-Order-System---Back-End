<?php
/**
 * Created by Md.Abdullah Al Mamun.
 * Email: mamun1214@gmail.com
 * Date: 4/28/2021
 * Time: 1:42 PM
 * Year: 2021
 */

namespace Alisra;

use DateTimeImmutable;
use PDO;

class Orders extends Helper
{
    public function __construct()
    {
        $db = new Database();
        $this->_db = $db->connect();
    }

    public function create($item)
    {
        if (!empty($item)) {
            if (isset($item['product_id']) && isset($item['total'])) {
                try {
                    $issuedAt = new DateTimeImmutable();
                    $createdAt = $issuedAt->getTimestamp();
                    $user = self::$_loggedIn;
                    $status = "Processing";
                    $query = "INSERT INTO orders (product_id,customer_id,total,status, created_at) VALUES  (:product, :customer, :total, :status, :datetime); ";
                    $stmt = $this->_db->prepare($query);
                    $stmt->bindParam(":product", $item['product_id']);
                    $stmt->bindParam(":customer", $user->id);
                    $stmt->bindParam(":total", $item['total']);
                    $stmt->bindParam(":status", $status);
                    $stmt->bindParam(":datetime", $createdAt);
                    return $stmt->execute();
                } catch (\PDOException $ex) {
                    throw new \Exception($ex->getMessage());
                }

            }
        }
        return false;
    }

    public function update($oid, $items)
    {
        try {
            if (!empty($items)) {
                $query = "UPDATE orders SET " . implode(",", $items) . " WHERE order_id=:oid";
                $stmt = $this->_db->prepare($query);
                $stmt->bindParam(":oid", $oid);
                return $stmt->execute();
            }
        } catch (\PDOException $ex) {
            throw new \Exception($ex->getMessage());
        }
        return false;
    }

    public function list()
    {
        $query = "SELECT o.order_id,p.name, p.sku, p.image, o.total, o.status, o.created_at FROM products as p INNER JOIN orders as o ON o.product_id=p.product_id ";
        if (!self::isAdmin()) {
            $query .= " WHERE customer_id=" . self::$_loggedIn->id;
        }
        $stmt = $this->_db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function __distruct()
    {

    }
}