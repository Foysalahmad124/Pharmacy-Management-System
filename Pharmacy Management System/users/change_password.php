<?php
session_start();
include("../includes/db_connect.php");

$username = $_SESSION['username'] ?? null;

if (!$username) {
    header("Location: ../login.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    $result = $conn->query("SELECT password FROM appuser WHERE username='$username'");
    $user = $result->fetch_assoc();

    if ($user && $user['password'] == $old_password) {
        if ($new_password == $confirm_password) {
            $update = "UPDATE appuser SET password='$new_password' WHERE username='$username'";
            if ($conn->query($update)) {
                $message = "<p style='color: lightgreen;'>Password updated successfully!</p>";
            } else {
                $message = "<p style='color: red;'>Error updating password: " . $conn->error . "</p>";
            }
        } else {
            $message = "<p style='color: red;'>New passwords do not match!</p>";
        }
    } else {
        $message = "<p style='color: red;'>Old password is incorrect!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <style>
        body {
            background-color: #008080; /* Teal background */
            font-family: Arial, sans-serif;
            padding: 30px;
            color: white;
        }
        .container {
            background: #ffffff;
            color: #333;
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }
        input[type="password"], button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background-color: #008080;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #006666;
        }
        a.btn-back {
            display: inline-block;
            margin-top: 15px;
            background: #3498db;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🔒 Change Password</h2>

    <?= $message ?>

    <form method="post">
        <label>Old Password:</label>
        <input type="password" name="old_password" required>

        <label>New Password:</label>
        <input type="password" name="new_password" required>

        <label>Confirm New Password:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Update Password</button>
    </form>

    <a href="profile.php" class="btn-back">⬅ Back to Profile</a>
</div>

<?php include("../includes/footer.php"); ?>

</body>
</html>
