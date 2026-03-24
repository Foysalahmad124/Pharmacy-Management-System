<?php
include("../includes/db_connect.php");

if (isset($_GET['date'])) {
    $date = $_GET['date'];

    $stmt = $conn->prepare("SELECT billID, billDate, totalPaid FROM bill WHERE DATE(billDate) = ? GROUP BY billID ORDER BY bill_pk DESC");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0' style='width: 100%; margin-top: 10px;'>";
        echo "<thead style='background: #e0f7fa;'>";
        echo "<tr><th>Bill ID</th><th>Date</th><th>Paid Amount (৳)</th></tr>";
        echo "</thead><tbody>";

        $grandTotal = 0;

        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['billID']) . "</td>";
            echo "<td>" . htmlspecialchars($row['billDate']) . "</td>";
            echo "<td>" . number_format($row['totalPaid'], 2) . "</td>";
            echo "</tr>";

            $grandTotal += $row['totalPaid'];
        }

        // Grand Total Row
        echo "<tr style='font-weight:bold; background:#f0f0f0;'>";
        echo "<td colspan='2'>Total Revenue</td>";
        echo "<td>" . number_format($grandTotal, 2) . "</td>";
        echo "</tr>";

        echo "</tbody></table>";
    } else {
        echo "<p style='color:red;'>No revenue found for this date.</p>";
    }
    $stmt->close();
} else {
    echo "<p style='color:red;'>Invalid request.</p>";
}
?>
