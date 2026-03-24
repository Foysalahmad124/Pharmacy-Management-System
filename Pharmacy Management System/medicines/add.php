<?php
include("../includes/header.php");
if (!$conn) { die("Please login first."); }

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"]; 
    $company = $_POST["companyName"];
    $qty = $_POST["quantity"]; 
    $price = $_POST["price"]; 
    $image_path = null;

    // --- Image Upload Logic ---
    if (isset($_FILES["medicine_image"]) && $_FILES["medicine_image"]["error"] == 0) {
        
        $target_dir = "../uploads/" . $_SESSION['database_name'] . "/";
        
        // --- নতুন সমাধান: ফোল্ডারটি না থাকলে স্বয়ংক্রিয়ভাবে তৈরি করুন ---
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        // --- সমাধান শেষ ---

        $image_file_name = uniqid() . '_' . basename($_FILES["medicine_image"]["name"]);
        $target_file = $target_dir . $image_file_name;
        
        if (move_uploaded_file($_FILES["medicine_image"]["tmp_name"], $target_file)) {
            $image_path = $image_file_name;
        } else { 
            $message = "<p style='color:red;'>File upload failed. Please check folder permissions.</p>"; 
        }
    }

    if (empty($message)) {
        $lastIdResult = $conn->query("SELECT uniqueId FROM medicine ORDER BY uniqueId DESC LIMIT 1");
        $nextUniqueId = ($lastIdResult && $lastIdResult->num_rows > 0) ? $lastIdResult->fetch_assoc()['uniqueId'] + 1 : 1;

        $stmt = $conn->prepare("INSERT INTO medicine (uniqueId, name, companyName, quantity, price, image_path) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssds", $nextUniqueId, $name, $company, $qty, $price, $image_path);
        
        if ($stmt->execute()) { 
            $message = "<p style='color:green;'>Medicine added successfully!</p>"; 
        } else { 
            $message = "<p style='color:red;'>Error: " . $stmt->error . "</p>"; 
        }
        $stmt->close();
    }
}
?>
<div class="form-container" style="max-width:500px;">
    <h2>➕ Add New Medicine</h2>
    <?= $message ?>
    <form method="post" enctype="multipart/form-data">
        <label>Medicine Name:</label><input type="text" name="name" required>
        <label>Company Name:</label><input type="text" name="companyName" required>
        <label>Quantity:</label><input type="number" name="quantity" required>
        <label>Price (৳):</label><input type="number" name="price" step="0.01" required>
        <label>Medicine Image:</label><input type="file" name="medicine_image">
        <button type="submit">✅ Add Medicine</button>
    </form>
    <a href="view.php" class="btn-back">⬅ Back to List</a>
</div>
<?php include("../includes/footer.php"); ?>