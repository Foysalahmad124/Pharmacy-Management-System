<?php
// This file connects manually to main DB, does not use header.php first
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') { die("Access Denied."); }

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $main_db_conn = new mysqli('localhost', 'root', '', 'jppharmacy');
    if ($main_db_conn->connect_error) { die("Main DB Connection Failed."); }

    // Collect and sanitize data
    $role = $_POST["userRole"]; $name = $_POST["name"]; $shop_name = $_POST["shop_name"];
    $dob = $_POST["dob"]; $mobile = $_POST["mobileNumber"]; $email = $_POST["email"];
    $username = $_POST["username"]; $password = $_POST["password"]; $address = $_POST["address"];
    
    // Use plain text password as requested by user
    $plain_password = $password; 
    
    $new_db_name = "pharmacy_" . preg_replace('/[^0-9]/', '', $mobile);
    $user_upload_folder = '../uploads/' . $new_db_name;

    // Create DB, Folder, Tables and User
    if ($main_db_conn->query("CREATE DATABASE " . $new_db_name)) {
        if (!is_dir($user_upload_folder)) { mkdir($user_upload_folder, 0777, true); }
        
        $user_db_conn = new mysqli('localhost', 'root', '', $new_db_name);
        if (!$user_db_conn->connect_error) {
            $sql_med = "CREATE TABLE `medicine` (`medicine_pk` int(11) NOT NULL AUTO_INCREMENT, `uniqueId` int(11) NOT NULL, `name` varchar(255) NOT NULL, `companyName` varchar(255) NOT NULL, `quantity` int(11) NOT NULL, `price` decimal(10,2) NOT NULL, `image_path` VARCHAR(255) NULL, PRIMARY KEY (`medicine_pk`)) ENGINE=InnoDB;";
            $sql_bill = "CREATE TABLE `bill` (`bill_pk` int(11) NOT NULL AUTO_INCREMENT, `billID` varchar(50) NOT NULL, `billDate` datetime NOT NULL, `totalPaid` decimal(10,2) NOT NULL, `generated` varchar(100) NOT NULL, `medicine_fk` int(11) NOT NULL, `quantity` int(11) NOT NULL, `price` decimal(10,2) NOT NULL, PRIMARY KEY (`bill_pk`)) ENGINE=InnoDB;";
            
            if ($user_db_conn->query($sql_med) && $user_db_conn->query($sql_bill)) {
                $stmt = $main_db_conn->prepare("INSERT INTO appuser (userRole, name, shop_name, dob, mobileNumber, email, username, password, address, user_database_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                // Storing plain password
                $stmt->bind_param("ssssssssss", $role, $name, $shop_name, $dob, $mobile, $email, $username, $plain_password, $address, $new_db_name);
                if ($stmt->execute()) { $message = "<p style='color:green;'>User, Database & Folder created successfully!</p>"; } 
                else { $message = "<p style='color:red;'>Error adding user: " . $stmt->error . "</p>"; }
            }
        }
    } else { $message = "<p style='color:red;'>Error creating database. A user with this phone number might already exist.</p>"; }
}
include("../includes/header.php"); // Include header for consistent look
?>
<div class="form-container" style="max-width:500px;">
    <h2>Add New User</h2>
    <?= $message ?>
    <form method="post">
        <label>Role:</label> <select name="userRole"><option value="Admin">Admin</option><option value="Pharmacist" selected>Pharmacist</option></select>
        <label>Name:</label> <input type="text" name="name" required>
        <label>Shop Name:</label> <input type="text" name="shop_name" required>
        <label>DOB:</label> <input type="date" name="dob" required>
        <label>Mobile (for DB name):</label> <input type="text" name="mobileNumber" required>
        <label>Email:</label> <input type="email" name="email" required>
        <label>Username:</label> <input type="text" name="username" required>
        <label>Password:</label> <input type="password" name="password" required>
        <label>Address:</label> <input type="text" name="address" required>
        <button type="submit">Add User</button>
    </form>
    <a href="../boss/dashboard.php" class="btn-back">🏠 Back to Dashboard</a>
</div>
<?php include("../includes/footer.php"); ?>