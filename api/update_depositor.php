<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/depositor.php';

$database = new Database();
$db = $database->getConnection();
$depositor = new Depositor($db);

$data = json_decode(file_get_contents("php://input"), true);


if (!empty($data['фио_вкладчика']) && !empty($data['номер_вклада'])) {
    if ($depositor->update($data)) {
        http_response_code(200);
        echo json_encode(array("message" => "Данные о вкладчике были успешно обновлены."), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503); 
        echo json_encode(array("message" => "Невозможно обновить данные вкладчика. Ошибка сервера БД."), JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Невозможно обновить данные вкладчика. Не хватает ключевых полей (ФИО или номер вклада)."), JSON_UNESCAPED_UNICODE);
}
?>