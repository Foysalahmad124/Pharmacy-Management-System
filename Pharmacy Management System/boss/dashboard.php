<?php include("../includes/header.php"); ?>

<div class="container text-center">
    <h2>Admin Dashboard</h2>
    <p class="lead">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></p>

    <div class="row" style="justify-content: center; display: flex; flex-wrap: wrap; gap: 20px;">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Manage Users</h5>
                <p class="card-text">Add, edit, or remove users.</p>
                <a href="../users/manage.php" class="btn-back" style="background-color:#007bff; color:white;">Go to Users</a>
            </div>
        </div>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Manage Medicines</h5>
                <p class="card-text">View, add, edit, and delete medicines.</p>
                <a href="../medicines/view.php" class="btn-back" style="background-color:#28a745; color:white;">Go to Medicines</a>
            </div>
        </div>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">View Reports</h5>
                <p class="card-text">See sales, inventory, and billing reports.</p>
                <a href="../reports/view.php" class="btn-back" style="background-color:#17a2b8; color:white;">View Reports</a>
            </div>
        </div>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Manage Profile</h5>
                <p class="card-text">Update your profile details.</p>
                <a href="../users/profile.php" class="btn-back" style="background-color:#ffc107; color:#212529;">Go to Profile</a>
            </div>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>