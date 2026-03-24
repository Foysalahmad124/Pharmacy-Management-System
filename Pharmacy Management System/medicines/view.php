<link rel="icon" type="image/x-icon" href="../images/janata.ico">
<?php
include("../includes/db_connect.php");
include("../includes/header.php");

$result = $conn->query("SELECT * FROM medicine");
?>

<h2>All Medicines</h2>
<a href="add.php" class="btn-add-new">➕ Add New Medicine</a>

<!-- Search Input -->
<input type="text" id="searchInput" placeholder="Search here..." style="margin: 10px 0; padding: 5px; width: 300px;">
<button onclick="searchTable()" class="btn-back" style="margin-left: 10px;">Search</button>
<button onclick="history.back()" class="btn-back" style="margin-left: 10px;">⬅ Back</button>

<!-- Medicine Table -->
<table id="dataTable" border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Unique ID</th>
            <th>Name</th>
            <th>Company</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
    <?php 
    $low_stock = ($row['quantity'] <= 10) ? "style='background-color: #ffcccc;'" : "";
    ?>
    <tr <?= $low_stock ?>>
        <td><?= htmlspecialchars($row['medicine_pk']) ?></td>
        <td><?= htmlspecialchars($row['uniqueId']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['companyName']) ?></td>
        <td><?= htmlspecialchars($row['quantity']) ?></td>
        <td><?= htmlspecialchars($row['price']) ?></td>
        <td>
            <a href="edit.php?id=<?= $row['medicine_pk'] ?>">Edit</a> |
            <a href="delete.php?id=<?= $row['medicine_pk'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<script>
// Search function
function searchTable() {
    var input = document.getElementById("searchInput");
    var filter = input.value.toLowerCase();
    var table = document.getElementById("dataTable");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) { // Skip header row
        var td = tr[i].getElementsByTagName("td");
        var found = false;
        for (var j = 0; j < td.length - 1; j++) { // Last column (Actions) skip
            if (td[j] && td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        tr[i].style.display = found ? "" : "none";
    }
}
</script>
<button onclick="history.back()" class="btn-back">⬅ Back</button
<?php include("../includes/footer.php"); ?>
