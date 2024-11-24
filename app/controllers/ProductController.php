<?php

namespace App\Controllers;

// Import autoload from vendor
require '../../vendor/autoload.php';

// Import database to use in the class
use App\Configs\Database;

// Import model of class
use App\Models\ProductModel;

// Declare date to use
date_default_timezone_set('America/Bogota');

class ProductController {
    
    private $db;
    private $pdo;

    public function __construct() {
        $this->db = new Database;
        $this->pdo = $this->db->getPDO();
    }

    public function createProduct($name, $price) {
        try {
            if(!$name || !$price) {
                return ["success" => false, "message" => "Los datos están incompletos, revisar la información enviada"];
            }

            $date_create = Date("Y-m-d");

            $creteProduct = new ProductModel;
            return $creteProduct->createProduct($name, $price, $date_create);
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    public function getProducts() {
        try {
            $getProducts = new ProductModel;
            return $getProducts->getProducts();
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    public function updateProduct($id, $name, $price) {
        try {
            $date_update = Date("Y-m-d");
            $updateProduct = new ProductModel;
            return $updateProduct->updateProduct($id, $name, $price, $date_update);
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

}


?>