<?php

// URL API
// http://localhost/lvl2/app/apis/LiquidationAPI.php

// Import autoload from vendor
require '../../vendor/autoload.php';

// Define access to use API
header("Access-Controll-Allow-Origin: *");
header("Content-Type: application/json");

// Get method (POST,GET, ETC.)
$method = $_SERVER["REQUEST_METHOD"];

// Get data from http
$data = json_decode(file_get_contents("php://input"), true);

// Start controller of liquidation
use App\Controllers\LiquidationController;


// USE FUNCTIONS BY METHOD

if($method == "POST") {

    createLiquidation($data);

} else if ($method == "GET") {
    
    filterGet($data);
    
} else if ($method == "PUT") {
    
    updateLiquidation($data);
    
} else if ($method == "DELETE") {
    
    deleteLiquidation($data);

} else {
    echo json_encode(["success" => false, "message" => "Error: Method not found"]);
}


// FUNCTIONS TO USE BY METHODS

function createLiquidation($data) {
    
    // JSON to use this function with postman
    // {
    //     "id_product": 1,
    //     "quantity_liters": 100,
    //     "farmer": "John Doe",
    //     "farm": "Sunny Fields",
    //     "id_operator": 1
    // }

    $createLiquidation = new LiquidationController();
    $response = $createLiquidation->createLiquidation($data["id_product"],$data["quantity_liters"],$data["farmer"],$data["farm"],$data["id_operator"]);
    echo json_encode($response);
}

// Function to filter method GET
function filterGet($data) {
    // Get filter from data send
    $filter = $data["filter"];

    // Declare ID's sends if was sends
    $id = isset($data["id"]) ? $data["id"] : NULL;

    switch($filter) {

        case 'all_records':
            getLiquidations();
            break;

        case 'single_record':
            getLiquidationById($id);
            break;
        
        case 'all_by_operator':
            getLiquidationsByOperator($id);
            break;

        default:
            echo json_encode(["success" => false, "message" => "Filtro no reconocido"]);
    }

}

function getLiquidations() {
    $getAllLiquidation = new LiquidationController();
    $response = $getAllLiquidation->getLiquidations();
    echo json_encode($response);
}

function getLiquidationById($id) {
    $getLiquidationById = new LiquidationController();
    $response = $getLiquidationById->getLiquidationById($id);
    echo json_encode($response);
}

function getLiquidationsByOperator($id) {
    $getLiquidationsByOperator = new LiquidationController();
    $response = $getLiquidationsByOperator->getLiquidationsByOperator($id);
    echo json_encode($response);
}

function updateLiquidation($data) {
    // JSON to use this function
    // {
    //     "id":17,
    //     "id_product": 2,
    //     "quantity_liters": 30,
    //     "farmer": "John Orejon",
    //     "farm": "PERÚ",
    //     "id_operator": 1
    // }

    $updateLiquidation = new LiquidationController();
    $response = $updateLiquidation->updateLiquidation($data["id"],$data["id_product"],$data["quantity_liters"],$data["farmer"],$data["farm"],$data["id_operator"]);
    echo json_encode($response);
}

function deleteLiquidation($data) {
    $id = $data["id"];

    $deleteLiquidation = new LiquidationController();
    $response = $deleteLiquidation->deleteLiquidation($id);
    echo json_encode($response);
}

?>