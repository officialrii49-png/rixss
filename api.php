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

/* =========================================
   POST DATA DARI ESP8266 / ESP32
========================================= */

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $data = json_decode(
        file_get_contents("php://input"),
        true
    );

    $sensor_id = $data['sensor_id'];
    $zone      = $data['zone'];
    $temp      = $data['temp'];
    $humidity  = $data['humidity'];

    $sql = "
    INSERT INTO sensor_log
    (sensor_id,zone,temperature,humidity)
    VALUES
    ('$sensor_id','$zone','$temp','$humidity')
    ";

    $conn->query($sql);

    echo json_encode([
        "status"=>"success"
    ]);

    exit;
}

/* =========================================
   GET DATA SENSOR TERBARU
========================================= */

$result = $conn->query("
SELECT *
FROM sensor_log
WHERE id IN (
    SELECT MAX(id)
    FROM sensor_log
    GROUP BY zone
)
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