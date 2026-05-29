<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "monitoring_suhu"
);

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=history_sensor.xls");

echo "
<table border='1'>

<tr>
    <th>Sensor</th>
    <th>Zona</th>
    <th>Suhu</th>
    <th>Kelembaban</th>
    <th>Waktu</th>
</tr>
";

$result = $conn->query("
SELECT *
FROM sensor_log
ORDER BY created_at DESC
");

while($row = $result->fetch_assoc()){

    echo "
    <tr>

        <td>".$row['sensor_id']."</td>
        <td>".$row['zone']."</td>
        <td>".$row['temperature']."°C</td>
        <td>".$row['humidity']."%</td>
        <td>".$row['created_at']."</td>

    </tr>
    ";

}

echo "</table>";

?>