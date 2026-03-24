<?php include("includes/header.php"); ?>

<div class="container text-center">
    <h2>Pharmacist Dashboard</h2>
    <p class="lead">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
    
    <div class="row" style="justify-content: center; display: flex; flex-wrap: wrap; gap: 20px;">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Manage Medicines</h5>
                <p class="card-text">View, add, edit, and delete medicines.</p>
                <a href="./medicines/view.php" class="btn-back" style="background-color:#007bff; color:white;">Go to Medicines</a>
            </div>
        </div>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Sell Medicine</h5>
                <p class="card-text">Go to the point-of-sale interface.</p>
                <a href="./medicines/sell.php" class="btn-back" style="background-color:#28a745; color:white;">Sell Dashboard</a>
            </div>
        </div>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">View Bills</h5>
                <p class="card-text">Review and manage billing history.</p>
                <a href="./bills/view.php" class="btn-back" style="background-color:#17a2b8; color:white;">View Bills</a>
            </div>
        </div>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Manage Profile</h5>
                <p class="card-text">Update your profile details.</p>
                <a href="./users/profile.php" class="btn-back" style="background-color:#ffc107; color:#212529;">Go to Profile</a>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>