<?php

namespace App\Controllers;

// Declare date to use
date_default_timezone_set('America/Bogota');

// import autoload from vendor
require '../../vendor/autoload.php';

// Import to use class Database and model
use App\Models\UserModel;
use App\Configs\Database;

class UserController {

    public $db;
    public $pdo;

    public function __construct() {
        $this->db = new Database;
        $this->pdo = $this->db->getPDO();
    }

    public function createUser($firstName, $secondName, $firstLastName, $secondLastName, $cc, $age, $email, $telephone, $position, $image, $password) {
        if(!$firstName || !$firstLastName  || !$cc || !$age || !$email || !$telephone || !$position || !$password) {
            return ["success" => false, "message" => "Datos incompletos, verifique la información"];
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "El correo electrónico no tiene un formato válido"];
        }

        if (!is_numeric($age) || $age <= 0) {
            return ["success" => false, "message" => "La edad debe ser un número mayor a 0"];
        }

        if(!$image || !$image["name"] || !$image["tmp_name"] || $image["error"]) {
            $image = 'image-default.jpg';
        } else {
            $image_tmp = $image["tmp_name"];
            $image = $image["name"];
            move_uploaded_file($image_tmp, "../../public/uploads/profile_pictures/".$image);
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        if(!$passwordHash) {
            return ["success" => false, "message" => "Error al intentar encriptar la contraseña"];
        }
        
        $date_created = Date("Y-m-d");

        try {
            $createUser = new UserModel;
            return $createUser->createUser($firstName, $secondName, $firstLastName, $secondLastName, $cc, $age, $email, $telephone, $position, $image, $passwordHash, $date_created);
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    public function getUserById($id) {
        try {
            if(!$id) {
                return ["success" => false, "message" => "Datos incompletos. Falta el parametro ID"];
            }

            if(!is_int($id)) {
                return ["success" => false, "message" => "Error. El parametro ID debe ser un número entero"];
            }
            $getUserById = new UserModel;
            return $getUserById->getUserById($id);
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

    public function updateUser($id, $firstName, $firstLastName,  $cc, $age, $email, $telephone, $password,  $secondName = NULL,$secondLastName = NULL, $image = NULL) {
        try {
            if(!$id || !$firstName || !$firstLastName  || !$cc || !$age || !$email || !$telephone || !$password) {
                return ["success" => false, "message" => "Datos incompletos, verifique la información"];
            }

            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            if($count <= 0) {
                return ["success" => false, "message" => "El usuario con ID $id no existe"];
            }

            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "El correo electrónico no tiene un formato válido"];
            }

            if (!is_numeric($age) || $age <= 0) {
                return ["success" => false, "message" => "La edad debe ser un número mayor a 0"];
            }
            
            if ($image) {
                $stmt = $this->pdo->prepare("SELECT image FROM users WHERE id = :id");
                $stmt->bindParam(":id", $id);
                $response = $stmt->fetch(PDO::FETCH_ASSOC);
                if($response == 'image-default.jpg') {
                    $image_tmp = $image["tmp_name"];
                    $image = $image["name"];
                    move_uploaded_file($image_tmp, "../../public/uploads/profile_pictures/".$image);
                } else {
                    if(file_exists("../../public/uploads/profile_pictures/".$response)) {
                        unlink("../../public/uploads/profile_pictures/".$response);
                    }

                    $image_tmp = $image["tmp_name"];
                    $image = $image["name"];
                    move_uploaded_file($image_tmp, "../../public/uploads/profile_pictures/".$image);
                }
            } else {
                $image = 'image-default.jpg';
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            if(!$passwordHash) {
                return ["success" => false, "message" => "Error al intentar encriptar la contraseña"];
            }
            
            $date_update = Date("Y-m-d");

            $updateUser = new UserModel;
            return $updateUser->updateUser($id, $firstName, $firstLastName, $cc, $age, $email, $telephone, $passwordHash, $date_update, $secondName, $secondLastName, $image);
        } catch (\Exception $e) {
            return ["success" => false, "message" => "Exception: ".$e->getMessage()];
        }
    }

}

?>