<?php

// URL API 
// http://localhost/lvl2/app/apis/UserAPI.php

// Import archive of autoload from vendor
require '../../vendor/autoload.php';

// Declare access to use API and format of
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Get and save data 
$data = json_decode(file_get_contents("php://input"), true);

// Get and save method to use in API
$method = $_SERVER["REQUEST_METHOD"];

// Start controller of user
use App\Controllers\UserController;

if($method == 'POST') {
    
    createUser($data);
    
} else if($method == 'GET') {
    
    getUserById($data);

} else if($method == 'PUT') {
    updateUser($data);
}


function createUser($data) {
    // JSON to use this function in postman
    // {
    //     "firstName": "Juan",
    //     "secondName": "Carlos",
    //     "firstLastName": "Pérez",
    //     "secondLastName": "Gómez",
    //     "cc": "123456789",
    //     "age": 30,
    //     "email": "juan.perez@example.com",
    //     "telephone": "+1234567890",
    //     "position": "Developer",
    //     "image": "https://example.com/images/profile.jpg",
    //     "password": "securePassword123"
    // }

    $createUser = new UserController;
    $response = $createUser->createUser($data['firstName'],$data['secondName'],$data['firstLastName'],$data['secondLastName'],$data['cc'],$data['age'],$data['email'],$data['telephone'],$data['position'],$data['image'],$data['password']);
    echo json_encode($response);
}

function getUserById($data) {
    $id = $data["id"];

    $getUserById = new UserController;
    $response = $getUserById->getUserById($id);
    echo json_encode($response);
}

function updateUser($data) {
    // JSON to use this function 
    // {
    //     "id": 123,
    //     "firstName": "Juan",
    //     "secondName": "Carlos",
    //     "firstLastName": "Pérez",
    //     "secondLastName": "Gómez",
    //     "cc": "123456789",
    //     "age": 30,
    //     "email": "juan.perez@example.com",
    //     "telephone": "+573001234567",
    //     "image": "https://example.com/images/juanperez.jpg",
    //     "password": "securepassword123"
    //   }      
    $updateUser = new UserController;
    $response = $updateUser->updateUser($data["id"],$data['firstName'],$data['firstLastName'],$data['cc'],$data['age'],$data['email'],$data['telephone'],$data['password'],$data['secondName'],$data['secondLastName'],$data['image']);
    echo json_encode($response);
}
  



?>