<?php
session_start();

// Already logged in? Redirect to dashboard
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] === 'Admin') { 
        header("Location: boss/dashboard.php"); 
    } else { 
        header("Location: dashboard.php"); 
    }
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn_main = new mysqli('localhost', 'root', '', 'jppharmacy');
    if ($conn_main->connect_error) { 
        die("Connection failed: " . $conn_main->connect_error); 
    }

    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn_main->prepare("SELECT * FROM appuser WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Simple plain-text password check
        if ($password === $user['password']) { 
            // Set session variables
            $_SESSION["username"] = $user["username"];
            $_SESSION["full_name"] = $user["name"];
            $_SESSION["role"] = $user["userRole"];
            $_SESSION["shop_name"] = $user["shop_name"];
            $_SESSION["database_name"] = "jppharmacy";

            // Redirect based on role
            if ($user['userRole'] === "Admin") {
                $_SESSION["shop_name"] = "Janata Pharmacy (Admin)";
                header("Location: boss/dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $error = "Incorrect username or password";
        }
    } else {
        $error = "Incorrect username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Pharmacy</title>
    <link rel="icon" type="image/x-icon" href="images/janata.ico">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header><h1>Pharmacy Management System</h1></header>
<main>
    <div class="form-container" style="max-width:400px; margin-top: 50px;">
        <h2>Login</h2>
        <form method="post" action="login.php">
            <label>Username:</label><input type="text" name="username" required>
            <label>Password:</label><input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (!empty($error)) echo "<p style='color:red;text-align:center;margin-top:15px;'>$error</p>"; ?>
    </div>
</main>
<footer><p>&copy; <?php echo date('Y'); ?> Pharmacy System.</p></footer>
</body>
</html>