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

    foreach($data['sensors'] as $sensor){

        $sensor_id =
            $sensor['sensor_id'];

        $zone =
            $sensor['zone'];

        $temp =
            $sensor['temp'];

        $humidity =
            $sensor['humidity'];

        $sql = "
        INSERT INTO sensor_log
        (sensor_id,zone,temperature,humidity)
        VALUES
        ('$sensor_id','$zone','$temp','$humidity')
        ";

        $conn->query($sql);
    }

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
ORDER BY sensor_id ASC
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