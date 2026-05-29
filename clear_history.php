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

$conn->query("DELETE FROM sensor_log");

echo json_encode([
    "status"=>"success"
]);

?>