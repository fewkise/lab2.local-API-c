<?php
error_reporting(E_ALL); 
ini_set('display_errors', 0);
ini_set('log_errors', 1);    

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/depositor.php';

$database = new Database();
$db = $database->getConnection();
$depositor = new Depositor($db);

$data = json_decode(file_get_contents("php://input"), true);

if (
    !empty($data['номер_вклада']) &&
    !empty($data['название_вклада']) &&
    !empty($data['фио_вкладчика']) &&
    !empty($data['сумма_вклада']) &&
    !empty($data['дата_вложения']) &&
    !empty($data['процент_начисления'])
) {
    if ($depositor->create($data)) {
        http_response_code(201);
        echo json_encode(array("message" => "Вкладчик был успешно добавлен."), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503); 
        echo json_encode(array("message" => "Невозможно добавить вкладчика."), JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(400); 
    echo json_encode(array("message" => "Невозможно добавить вкладчика. Данные неполные."), JSON_UNESCAPED_UNICODE);
}
?>
