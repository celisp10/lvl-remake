<?php

// Declare namespace to the class
namespace App\Models;

// import autoload from vendor
require '../../vendor/autoload.php';

// Import to use class Database
use App\Configs\Database;

class UserModel {

    private $db;
    private $pdo;

    public function __construct() {
        $this->db = new Database;
        $this->pdo = $this->db->getPDO();
    }

    public function createUser($firstName, $secondName, $firstLastName, $secondLastName, $cc, $age, $email, $telephone, $position, $image, $password, $date_created) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (first_name, second_name, first_lastname, second_lastname, cc, age, email, telephone, position, image, password, date_created) 
                VALUES (:firstname, :secondname, :firstlastname, :secondlastname, :cc, :age, :email, :telephone, :position, :image, :password, :date_created)");
        
                $stmt->bindParam(":firstname", $firstName);
                $stmt->bindParam(":secondname", $secondName);
                $stmt->bindParam(":firstlastname", $firstLastName);
                $stmt->bindParam(":secondlastname", $secondLastName);
                $stmt->bindParam(":cc", $cc);
                $stmt->bindParam(":age", $age);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":telephone", $telephone);
                $stmt->bindParam(":position", $position);
                $stmt->bindParam(":image", $image);
                $stmt->bindParam(":password", $password);
                $stmt->bindParam(":date_created", $date_created);

                if($stmt->execute()) {
                    return ["success" => true, "message" => "Usuario $firstName creado con exito en la fecha: $date_created"];
                } else {
                    return ["success" => false, "message" => "Hubo un error al tratar de crear el usuario"];
                }
        } catch (\Exception $e) {
                return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    // ### REMEMBER TO MODIFICATE THIS FUNCTION TO JUST GET EXPECIFIC DATA IF IT'S NECESARY ###
    public function getUserById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(":id", $id);

            if($stmt->execute()) {
                $response = $stmt->fetch(\PDO::FETCH_ASSOC);
                if($response) {
                    return ["success" => true, "message" => "Usuario con ID: $id obtenido correctamente", "data" => $response];
                } else {
                    return ["success" => false, "message" => "Usuario con ID: $id no encontrado"];
                }
            } else {
                return ["success" => false, "message" => "Error al intentar obtener el usuario"];
            }
        } catch(\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    public function updateUser($id, $firstName, $firstLastName, $cc, $age, $email, $telephone, $password, $date_update, $secondName = NULL, $secondLastName = NULL, $image = NULL) {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET first_Name = :firstName, second_Name = :secondName, first_LastName = :firstLastName, second_LastName = :secondLastName, cc = :cc, age = :age, email = :email, telephone = :telephone, image = :image, password = :password, date_update = :date_update
            WHERE  id = :id;");
            $stmt->bindParam(':firstName', $firstName, \PDO::PARAM_STR);
            $stmt->bindParam(':secondName', $secondName, \PDO::PARAM_STR);
            $stmt->bindParam(':firstLastName', $firstLastName, \PDO::PARAM_STR);
            $stmt->bindParam(':secondLastName', $secondLastName, \PDO::PARAM_STR);
            $stmt->bindParam(':cc', $cc, \PDO::PARAM_STR); // Documento, asumido como texto (si es numérico, cambiar a \PDO::PARAM_INT)
            $stmt->bindParam(':age', $age, \PDO::PARAM_INT); // Edad, asumido como entero
            $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $telephone, \PDO::PARAM_STR);
            $stmt->bindParam(':image', $image, \PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, \PDO::PARAM_STR); // Contraseña encriptada
            $stmt->bindParam(':date_update', $date_update); // Fecha de actualización
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT); // ID del usuario

            if($stmt->execute()) {
                return ["success" => true, "message" => "Usuario con id $id actualizado exitosamente"];
            } else {
                return ["success" => false, "message" => "Error al tratar de actualizar el usuario"];
            }
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

}




?> 