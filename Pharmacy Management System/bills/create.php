<link rel="icon" type="image/x-icon" href="../images/janata.ico">
<?php
session_start();
include("../includes/db_connect.php");
include("../includes/header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST["billDate"];
    $total = $_POST["totalPaid"];
    $user = $_SESSION["username"];
    $billID = "Bill-" . rand(100000000000000, 999999999999999);

    $sql = "INSERT INTO bill (billID, billDate, totalPaid, generated)
            VALUES ('$billID', '$date', '$total', '$user')";

    if ($conn->query($sql)) {
        echo "<p style='color:green;'>Bill created successfully with ID: $billID</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}
?>

<h2>Create New Bill</h2>
<form method="post">
    Bill Date: <input type="date" name="billDate" required><br>
    Total Paid: <input type="number" name="totalPaid" required><br>
    <button type="submit">Create Bill</button>
</form>

<?php include("../includes/footer.php"); ?>
