<?php

namespace App\Models;

// Import autoload from vendor
require '../../vendor/autoload.php';

// Import database to use in the class
use App\Configs\Database;

class ProductModel {

    private $db;
    private $pdo;

    public function __construct() {
        $this->db = new Database;
        $this->pdo = $this->db->getPDO();
    }

    public function createProduct($name, $price, $date_created) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO products (name, price, date_created) VALUES (:name, :price, :date_created)");
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":date_created", $date_created);
            if($stmt->execute()) {
                return ["success" => true, "message" => "Producto creado exitosamente"];
            } else {
                return ["success" => false, "message" => "Error al tratar de crear el producto $name"];
            }
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    // Get all products
    public function getProducts() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products");
            if($stmt->execute()) {
                $response = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                if(!$response) {
                    return["success" => false, "message" => "No se encontró ningun producto registrado"];
                }
                return ["success" => true, "message" => "Datos obtenidos exitosamente", "data" => $response];
            } else {
                return ["sucess" => false, "message" => "Error al tratar de obtener todos los datos"];
            }
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    public function updateProduct($id, $name, $price, $date_update) {
        try {
            $stmt = $this->pdo->prepare("UPDATE products SET name=:name, price=:price, date_update=:date_update WHERE id = :id");
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":date_update", $date_update);
            $stmt->bindParam(":id", $id);
            if($stmt->execute()) {
                return ["success" => true, "message" => "Producto actualizado con exito"];
            } else {
                return ["success" => false, "message" => "Error al tratar de actualizar el producto"];
            }
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

}




?>