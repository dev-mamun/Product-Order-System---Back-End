<?php
/**
 * Created by Md.Abdullah Al Mamun.
 * Email: mamun1214@gmail.com
 * Date: 4/23/2021
 * Time: 5:12 PM
 * Year: 2021
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/
require_once 'vendor/autoload.php';


/*$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'http://jsonplaceholder.typicode.com/users');

echo $response->getStatusCode();
echo $response->getHeaderLine('content-type');
echo $response->getBody();*/

use Alisra\Router;
use Alisra\Products;
use Alisra\Users;
use Alisra\Orders;

$route = new Router();

$route->add('/', function () {
    echo "This is root";
    echo "<pre>";
    print_r($_SERVER);
    echo "</pre>";
});
$route->add('/login', function () {
    $status = false;
    $msg = "Invalid Credentials";
    $data = [];
    if ((isset($_POST['username']) && !empty($_POST['username'])) && (isset($_POST['password']) && !empty($_POST['password']))) {
        $username = strip_tags($_POST['username']);
        $password = strip_tags($_POST['password']);
        $user = new Users();
        $result = $user->login($username, $password);
        if ($result) {
            $status = true;
            $msg = "Login Success";
            $data = [
                "token" => \Alisra\Helper::getToken($result),
                "name" => $result->full_name,
                "redirect" => \Alisra\Helper::getRedirectUrl($result)
            ];
        }
    }
    \Alisra\Helper::response($status, $msg, $data);
});

$route->add('/logout', function () {
    echo "This is " . $_SERVER['REQUEST_URI'];

});
$route->add('/products', function () {
    echo "This is " . $_SERVER['REQUEST_URI'];
});
$route->add('/product/{:id}', function () {
    echo "This is " . $_SERVER['REQUEST_URI'];
});
$route->add('/add/product', function () {
    $status = false;
    $msg = "Could not add product";
    $data = [];
    if (\Alisra\Helper::hasPermission()) {
        $isValidate = \Alisra\Helper::validateInput();
        if ($isValidate) {
            $product = new Products();
            $created = $product->create($isValidate);
            if ($created) {
                $status = true;
                $msg = "Product Add successfully";
                $data = $_POST;
            }
        }
    }
    \Alisra\Helper::response($status, $msg, $data);
});
$route->add('/edit/product', function () {
    echo "This is " . $_SERVER['REQUEST_URI'];
});
$route->add('/update/status/', function () {
    $status = false;
    $msg = "Could not update order";
    if (\Alisra\Helper::hasPermission()) {
        if(isset($_POST['oid']) && !empty($_POST['oid'])){
            $oid = $_POST['oid'];
            $order = new Orders();
            if($order->update($oid, ["status='Shipped'"])){
                $status = true;
                $msg = "Order Status update successfully";
            }
        }
    }
    \Alisra\Helper::response($status, $msg);
});
$route->add('/create/order', function () {
    $status = false;
    $msg = "Could not create order";
    $data = [];
    if(\Alisra\Helper::isLoggedIn()){
        $order = new Orders();
        if($order->create($_POST)){
            $status = true;
            $msg = "Order Add successfully";
            $data = [];
        }
    }
    \Alisra\Helper::response($status, $msg, $data);
});
$route->add('/orders', function () {
    $status = false;
    $msg = "Could not fetch order list";
    $data = [];
    if(\Alisra\Helper::isLoggedIn()){
        $order = new Orders();
        $status = false;
        $msg = "Order list fetch successfully";
        $data = $order->list();
    }
    \Alisra\Helper::response($status, $msg, $data);
});


$route->dispatch();

/*echo "<pre>";
print_r($route);
echo "</pre>";*/