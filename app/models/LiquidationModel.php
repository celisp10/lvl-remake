<?php

namespace App\Models;

// import autoload from vendor
require '../../vendor/autoload.php';

use App\Configs\Database;

// Design and create model of liquidation
class LiquidationModel {

    // Dependence of DB in class
    private $db;
    private $pdo;
    
    public function __construct() {
        $this->db = new Database;
        $this->pdo = $this->db->getPDO();
    }

    // Function to create and save liquidation in database
    public function createLiquidation($id_product,$total_price,$quantity_liters,$farmer,$farm,$date_created,$id_operator) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO liquidations (id_product, total_price, quantity_liters, farmer, farm, date_created, id_operator) 
            VALUES (:id_product, :total_price, :quantity_liters, :farmer, :farm, :date_created, :id_operator)");
    
            $stmt->bindParam(":id_product", $id_product);
            $stmt->bindParam(":total_price", $total_price);
            $stmt->bindParam(":quantity_liters", $quantity_liters);
            $stmt->bindParam(":farmer", $farmer);
            $stmt->bindParam(":farm", $farm);
            $stmt->bindParam(":date_created", $date_created);
            $stmt->bindParam(":id_operator", $id_operator);
            if($stmt->execute()) {
                return ["success" => true, "message" => "Registro de liquidación guardado exitosamente"];
            } else {
                return ["success" => false, "message" => "Error al intentar guardar la liquidación en la base de datos"];
            }
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception in model with operator -$id_operator- : ".$e->getMessage()];
        }
        
    }

    // Function to get all records
    public function getLiquidations() {
        try {
            $stmt = $this->pdo->prepare("SELECT l.*, p.name AS product_name, u.first_name AS operator_first_name, u.first_lastname AS operator_first_lastname
            FROM liquidations l JOIN products p ON l.id_product = p.id JOIN users u ON l.id_operator = u.id");
            if($stmt->execute()) {
                $resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                return ["success" => true, "message" => "Datos obtenidos exitosamente", "data" => $resultado];
            } else {
                return ["success " => false, "message" => "Error al obtener todas las liquidaciones"];
            }
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }
    
    // Function to get one liquidaton by it's ID
    public function getLiquidationById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM liquidations WHERE id = :id");
            $stmt->bindParam(":id", $id);
            if($stmt->execute()) {
                $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
                if(!$resultado) {
                    return ["success" => false, "message" => "Liquidación con ID $id no encontrada"];
                }
                return ["success" => true, "message" => "Liquidación con ID: $id obtenida exitosamente", "data" => $resultado];
            } else {
                return ["success" => false, "message" => "Error al intentar obtener la liquidación con ID: $id"];
            }
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    // Function to get all records of one operator
    public function getLiquidationsByOperator($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT l.*, p.name AS product_name, u.first_name AS operator_first_name, u.first_lastname AS operator_first_lastname
            FROM liquidations l JOIN products p ON l.id_product = p.id JOIN users u ON l.id_operator = u.id WHERE id_operator = $id");
            if($stmt->execute()) {
                $resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                if(!$resultado) {
                    return ["success" => false, "message" => "No se ha encontrado ningun registro del usuario con ID: $id"];
                }
                return ["sucess" => true, "message" => "Registros obtenidos con exito", "data" => $resultado];
            } else {
                return ["success" => false, "message" => "Hubo un error al intentar obtener los datos"];
            }
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }
    
    // Function to update liquidation register
    public function updateLiquidation($id,$id_product,$total_price,$quantity_liters,$farmer,$farm,$date_update,$id_operator) {
        try {
            // Verify if the ID exists
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM liquidations WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $rowCount = $stmt->fetchColumn();

            // If the result is 0 or null
            if($rowCount == 0) {
                return ["success" => false, "message" => "Error: No existe ningun registro con el ID: $id"];
            }

            $stmt = $this->pdo->prepare("UPDATE liquidations SET id_product = :id_product, total_price = :total_price, quantity_liters = :quantity_liters, farmer = :farmer, farm = :farm, date_update = :date_update, id_operator = :id_operator WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":id_product", $id_product);
            $stmt->bindParam(":total_price", $total_price);
            $stmt->bindParam(":quantity_liters", $quantity_liters);
            $stmt->bindParam(":farmer", $farmer);
            $stmt->bindParam(":farm", $farm);
            $stmt->bindParam(":date_update", $date_update);
            $stmt->bindParam(":id_operator", $id_operator);
            if($stmt->execute()) {
                return ["success" => true, "message" => "Liquidación con ID $id fue actualizada con exito"];
            } else {
                return ["success" => false, "message" => "Hubo un error al intentar actualizar la liquidación con ID $id"];
            }
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    public function deleteLiquidation($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM liquidations WHERE id = :id");
            $stmt->bindParam(":id", $id);
            if($stmt->execute()) {
                return ["succes" => true, "message" => "Registro de liquidación eliminado exitosamente"];
            } else {
                return ["success" => false, "message" => "Hubo un error al intentar eliminar el registro"];
            }
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

}

?>

