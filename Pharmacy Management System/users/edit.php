<link rel="icon" type="image/x-icon" href="../images/janata.ico">
<?php
include("../includes/db_connect.php");
include("../includes/header.php");

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM appuser WHERE appuser_pk=$id");
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST["userRole"];
    $name = $_POST["name"];
    $dob = $_POST["dob"];
    $mobile = $_POST["mobileNumber"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $address = $_POST["address"];

    $sql = "UPDATE appuser SET userRole='$role', name='$name', dob='$dob', mobileNumber='$mobile', 
            email='$email', username='$username', password='$password', address='$address' 
            WHERE appuser_pk=$id";
    if ($conn->query($sql)) {
        header("Location: view.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<h2>Edit User</h2>
<form method="post">
    Role: <select name="userRole">
        <option value="Admin" <?= $user['userRole'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
        <option value="Pharmacist" <?= $user['userRole'] == 'Pharmacist' ? 'selected' : '' ?>>Pharmacist</option>
    </select><br>
    Name: <input type="text" name="name" value="<?= $user['name'] ?>" required><br>
    DOB: <input type="date" name="dob" value="<?= $user['dob'] ?>" required><br>
    Mobile: <input type="text" name="mobileNumber" value="<?= $user['mobileNumber'] ?>" required><br>
    Email: <input type="email" name="email" value="<?= $user['email'] ?>" required><br>
    Username: <input type="text" name="username" value="<?= $user['username'] ?>" required><br>
    Password: <input type="password" name="password" value="<?= $user['password'] ?>" required><br>
    Address: <input type="text" name="address" value="<?= $user['address'] ?>" required><br>
    <button type="submit">Update User</button>
</form>

<?php include("../includes/footer.php"); ?>
