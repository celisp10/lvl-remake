<?php

namespace App\Controllers;

// Declare date to use
date_default_timezone_set('America/Bogota');

// import autoload from vendor
require '../../vendor/autoload.php';

// Import model and Database
use App\Models\LiquidationModel;
use App\Configs\Database;

// Design and create controller of liquidation
class LiquidationController {

    public $db;
    public $pdo;

    public function __construct() {
        $this->db = new Database;
        $this->pdo = $this->db->getPDO();
    }

    public function createLiquidation($id_product,$quantity_liters,$farmer,$farm,$id_operator) {

        if(!$id_product || !$quantity_liters || !$farmer || !$farm || !$id_operator) {
            return ["success" => false, "message" => "Error: Datos incompletos. No se pudo guardar la liquidación."];
        }

        try {
            // Sentence SQL to get the price product
            $stmt = $this->pdo->prepare("SELECT price FROM products WHERE id = :id");
            $stmt->bindParam(":id", $id_product);
            $stmt->execute();
            $product = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            // If product is not found, send an error
            if(!$product["price"] || !$product) {
                return ["success" => false, "message" => "Error: Producto no encontrado"];
            }
    
            // Declare the price of the product to calculate the total price of the liquidation
            $price = $product["price"]; // After get the price by sentence SQL
    
            // Declare date
            $date_created = Date("Y-m-d");
    
            // Calculate total price
            $total_price = $price * $quantity_liters;
    
            $createLiquidation = new LiquidationModel();
            return $createLiquidation->createLiquidation($id_product,$total_price,$quantity_liters,$farmer,$farm,$date_created,$id_operator);
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
        
    }

    public function getLiquidations() {
        try {
            $getAllLiquidations = new LiquidationModel();
            return $getAllLiquidations->getLiquidations();
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    public function getLiquidationById($id) {
        try {
            $getLiquidationById = new liquidationModel();
            return $getLiquidationById->getLiquidationById($id);
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    public function getLiquidationsByOperator($id) {
        try {
            $getLiquidationsByOperator = new LiquidationModel();
            return $getLiquidationsByOperator->getLiquidationsByOperator($id);
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    public function updateLiquidation($id,$id_product,$quantity_liters,$farmer,$farm,$id_operator) {

        // #### REMEMBER TO SEARCH FOR THE PRODUCT BY ITS ID AND NOT GET THE PRICE BY PARAMETER ####

        if(!$id_product || !$quantity_liters || !$farmer || !$farm || !$id_operator) {
            throw new \Exception("Error: Data not received. Unable to update");
        }

        // --- Sentence SQL to get the price product here ---
        
        $price = 4500;

        $date_update = Date("Y-m-d");
        $total_price = $price * $quantity_liters;

        try {
            $updateLiquidation = new LiquidationModel();
            return $updateLiquidation->updateLiquidation($id,$id_product,$total_price,$quantity_liters,$farmer,$farm,$date_update,$id_operator);
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    public function deleteLiquidation($id) {
        try {
            $deleteLiquidation = new LiquidationModel();
            return $deleteLiquidation->deleteLiquidation($id);
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Error: ".$e->getMessage()];
        }
    }
}
?>
