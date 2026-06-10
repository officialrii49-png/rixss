<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "monitoring_suhu"
);

if($conn->connect_error){
    die("Koneksi gagal");
}

$scriptURL =
"https://script.google.com/macros/s/AKfycbzEwhv234d3q_V9UzEqHstJa8sSlpS1xNl_K_wt9nvfab7LRJjAx1HrEEY6jB5tlt3cCw/exec";

$result = $conn->query("
SELECT *
FROM sensor_log
WHERE synced = 0
ORDER BY id ASC
LIMIT 50
");

while($row = $result->fetch_assoc()){

    $payload = [

        "sensor"   => $row['sensor_id'],
        "zone"     => $row['zone'],
        "temp"     => $row['temperature'],
        "humidity" => $row['humidity']

    ];

    $ch = curl_init($scriptURL);

    curl_setopt(
        $ch,
        CURLOPT_RETURNTRANSFER,
        true
    );

    curl_setopt(
        $ch,
        CURLOPT_POST,
        true
    );

    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        [
            "Content-Type: application/json"
        ]
    );

    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        json_encode($payload)
    );

    $response = curl_exec($ch);

    curl_close($ch);

    $sqlUpdate = "
    UPDATE sensor_log
    SET synced = 1
    WHERE id = ".$row['id'];

    $resultUpdate = $conn->query($sqlUpdate);

    if(!$resultUpdate){
        echo "UPDATE ERROR: " . $conn->error;
    }

}

echo "SYNC SUCCESS";