<link rel="icon" type="image/x-icon" href="../images/janata.ico">
<?php
include("../includes/db_connect.php");
include("../includes/header.php");

$id = $_GET['id'];
$sql = "SELECT * FROM medicine WHERE medicine_pk=$id";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uniqueId = $_POST["uniqueId"];
    $name = $_POST["name"];
    $company = $_POST["companyName"];
    $qty = $_POST["quantity"];
    $price = $_POST["price"];

    $update = "UPDATE medicine SET uniqueId='$uniqueId', name='$name', companyName='$company', 
               quantity='$qty', price='$price' WHERE medicine_pk=$id";
    if ($conn->query($update)) {
        header("Location: view.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<h2>Edit Medicine</h2>
<form method="post">
    Unique ID: <input type="text" name="uniqueId" value="<?= $data['uniqueId'] ?>" required><br>
    Name: <input type="text" name="name" value="<?= $data['name'] ?>" required><br>
    Company: <input type="text" name="companyName" value="<?= $data['companyName'] ?>" required><br>
    Quantity: <input type="number" name="quantity" value="<?= $data['quantity'] ?>" required><br>
    Price: <input type="number" name="price" value="<?= $data['price'] ?>" required><br>
    <button type="submit">Update</button>
</form>
<button onclick="history.back()" class="btn-back">⬅ Back</button>

<?php include("../includes/footer.php"); ?>
