<?php
session_start();
date_default_timezone_set('Asia/Dhaka');

// Set header for a clear text response, which the JS expects
header('Content-Type: text/plain'); 

// --- Authentication and Connection ---
if (!isset($_SESSION['username']) || !isset($_SESSION['database_name'])) {
    die('Error: Authentication failed. Please login again.');
}

$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$user_db_name = $_SESSION['database_name'];

$conn = new mysqli($host, $db_user, $db_pass, $user_db_name);
if ($conn->connect_error) {
    die('Error: Database connection failed.');
}

// --- Get Data and Process Sale ---
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || count($data) == 0) {
    die('Error: No sale data received.');
}

$conn->begin_transaction();

try {
    $totalPaid = 0;
    foreach ($data as $item) {
        if (!isset($item['price']) || !isset($item['quantity'])) {
            throw new Exception('Invalid item data in cart.');
        }
        $totalPaid += $item['price'] * $item['quantity'];
    }

    $billID = 'BILL-' . rand(10000, 99999);
    $billDate = date('Y-m-d H:i:s');
    
    // --- এই লাইনে পরিবর্তন আনা হয়েছে ---
    // এখন থেকে ইউজারনেমের বদলে ব্যবহারকারীর সম্পূর্ণ নামটি সেভ হবে
    $generatedBy = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : $_SESSION['username'];
    // --- পরিবর্তন শেষ ---

    $stmt_bill = $conn->prepare("INSERT INTO bill (billID, billDate, totalPaid, generated, medicine_fk, quantity, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt_stock = $conn->prepare("UPDATE medicine SET quantity = quantity - ? WHERE medicine_pk = ? AND quantity >= ?");

    foreach ($data as $item) {
        $medicineId = (int)$item['id'];
        $quantity = (int)$item['quantity'];
        $price = (float)$item['price'];

        $stmt_bill->bind_param("ssdsiid", $billID, $billDate, $totalPaid, $generatedBy, $medicineId, $quantity, $price);
        $stmt_bill->execute();

        $stmt_stock->bind_param("iii", $quantity, $medicineId, $quantity);
        $stmt_stock->execute();
        
        if ($stmt_stock->affected_rows == 0) {
            throw new Exception("Not enough stock for medicine ID " . $medicineId . ".");
        }
    }
    
    $stmt_bill->close();
    $stmt_stock->close();
    
    $conn->commit();
    
    echo "Sale successful! Bill ID: " . $billID;

} catch (Exception $e) {
    $conn->rollback();
    echo 'Error: ' . $e->getMessage();
}

$conn->close();
?>