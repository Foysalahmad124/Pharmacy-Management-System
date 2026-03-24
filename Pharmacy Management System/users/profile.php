<link rel="icon" type="image/x-icon" href="../images/janata.ico">
<?php
session_start();
include("../includes/db_connect.php");

$username = $_SESSION['username'] ?? null;

if (!$username) {
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("SELECT * FROM appuser WHERE username='$username'");
$user = $result->fetch_assoc();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $dob = $_POST["dob"];
    $mobile = $_POST["mobileNumber"];
    $email = $_POST["email"];
    $address = $_POST["address"];

    $update = "UPDATE appuser SET name='$name', dob='$dob', mobileNumber='$mobile', email='$email', address='$address' WHERE username='$username'";
    if ($conn->query($update)) {
        $message = "<p style='color: lightgreen;'>Profile updated successfully!</p>";
        header("refresh:1");
    } else {
        $message = "<p style='color: red;'>Error updating profile: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <style>
        body {
            background-color: #008080;
            font-family: Arial, sans-serif;
            color: #ffffff;
            padding: 30px;
            margin: 0;
        }
        .container {
            background: #ffffff;
            color: #333;
            max-width: 500px;
            margin: 50px auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }
        input[type="text"], input[type="email"], input[type="date"], button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button[type="submit"] {
            background-color: teal;
            color: white;
            border: none;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #006666;
        }
        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #3498db;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        a.btn-change-password {
            display: inline-block;
            margin-top: 10px;
            background: #3498db;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .message {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Back Button -->
    <a href="../dashboard.php" class="btn-back">⬅ Back to Dashboard</a>

    <h2>📝 My Profile</h2>

    <div class="message"><?= $message ?></div>

    <form method="post">
        Name:
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        Date of Birth:
        <input type="date" name="dob" value="<?= htmlspecialchars($user['dob']) ?>" required>

        Mobile:
        <input type="text" name="mobileNumber" value="<?= htmlspecialchars($user['mobileNumber']) ?>" required>

        Email:
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        Address:
        <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" required>

        <button type="submit">Update Profile</button>
    </form>

    <a href="change_password.php" class="btn-change-password">🔒 Change Password</a>
</div>

<?php include("../includes/footer.php"); ?>

</body>
</html>
