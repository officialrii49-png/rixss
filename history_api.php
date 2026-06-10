<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "monitoring_suhu"
);

if($conn->connect_error){
    die("Koneksi gagal");
}

$result = $conn->query("
SELECT *
FROM sensor_log
ORDER BY id DESC
");

$sensors = [];

while($row = $result->fetch_assoc()){

    $sensors[] = [

        "id"=>$row['sensor_id'],
        "zone"=>$row['zone'],
        "temp"=>$row['temperature'],
        "humidity"=>$row['humidity'],
        "time"=>$row['created_at']

    ];

}

echo json_encode([
    "sensors"=>$sensors
]);

?>