<?php
include("../includes/db_connect.php");

$result = $conn->query("SELECT * FROM medicine");
$medicines = [];

while ($row = $result->fetch_assoc()) {
    $medicines[] = $row;
}

echo json_encode($medicines);
?>
