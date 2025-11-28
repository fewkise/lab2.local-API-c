<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/depositor.php'; 


$database = new Database();
$db = $database->getConnection();
$depositor = new Depositor($db);

$stmt = $depositor->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $response_arr = array();
    $response_arr["records"] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $depositor_item = array(
            "номер_вклада" => $row['номер вклада'],
            "название_вклада" => $row['название вклада'],
            "фио_вкладчика" => $row['ФИО вкладчика'],
            "сумма_вклада" => $row['сумма вклада'],
            "дата_вложения" => $row['дата вложения'],
            "процент_начисления" => $row['процент начисления'],
            "общая_сумма_с_начислениями" => $row['общая сумма с начислениями']
        );

        array_push($response_arr["records"], $depositor_item);
    }

    $totalInterest = $depositor->getTotalInterestSum();
    $response_arr["total_interest_sum"] = $totalInterest;

    http_response_code(200);

    echo json_encode($response_arr, JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Данные о вкладах не найдены."), JSON_UNESCAPED_UNICODE);
}
?>