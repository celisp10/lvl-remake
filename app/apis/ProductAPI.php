<?php

// URL API
// http://localhost/lvl2/app/apis/ProductAPI.php

header("Access-Control-Allow-Origin: *");

// Import autoload from vendor
require '../../vendor/autoload.php';

// Import controller of class
use App\Controllers\ProductController;

// Get method
$method = $_SERVER["REQUEST_METHOD"];

// Get data from http
$data = json_decode(file_get_contents("php://input"), true);

if($method == "POST") {
    
    createProduct($data);
    
} else if($method == "GET") {

    getProducts();

} else if($method == "PUT") {

    updateProduct($data);

}

function createProduct($data) {
    $createProduct = new ProductController;
    $response = $createProduct->createProduct($data["name"], $data["price"]);
    echo json_encode($response);
}

function getProducts() {
    $getProducts = new ProductController;
    $response = $getProducts->getProducts();
    echo json_encode($response);
}

function updateProduct($data) {
    $updateProduct = new ProductController;
    $response = $updateProduct->updateProduct($data["id"], $data["name"], $data["price"]);
    echo json_encode($response);
}

?>