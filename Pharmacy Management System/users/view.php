<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') { die("Access Denied."); }

$conn_main = new mysqli('localhost', 'root', '', 'jppharmacy');
if ($conn_main->connect_error) { die("Main DB Connection Failed."); }

$result = $conn_main->query("SELECT appuser_pk, userRole, name, shop_name, mobileNumber, username FROM appuser ORDER BY appuser_pk DESC");

include("../includes/header.php");
?>
<div class="container">
    <h2>All Users</h2>
    <a href="add.php" class="btn-add-new">➕ Add New User</a>
    <table>
        <thead>
            <tr><th>ID</th><th>Role</th><th>Name</th><th>Shop Name</th><th>Mobile</th><th>Username</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['appuser_pk'] ?></td>
                <td><?= htmlspecialchars($row['userRole']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['shop_name']) ?></td>
                <td><?= htmlspecialchars($row['mobileNumber']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['appuser_pk'] ?>">Edit</a> |
                    <a href="delete.php?id=<?= $row['appuser_pk'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="../boss/dashboard.php" class="btn-back">⬅ Back to Dashboard</a>
</div>
<?php
$conn_main->close();
include("../includes/footer.php");
?>