<?php
include("../includes/db_connect.php");

$id = $_GET['id'];
$conn->query("DELETE FROM appuser WHERE appuser_pk=$id");

header("Location: view.php");
exit();
