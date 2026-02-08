
<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Dashboard | Campus Placement</title>
<link rel="stylesheet" href="../assets/dashboard.css">
</head>
<body>

<!-- TOP COLLEGE IMAGE -->
<section class="banner">
    <div class="banner-overlay">
        <h1>G. Venkatasamy Naidu College</h1>
        <p>Campus Placement Portal</p>
    </div>
</section>

<!-- NAVBAR -->
<header class="navbar">
    <div class="logo">
        <div class="logo-box">GVN</div>
        <div class="logo-text">Campus Placement</div>
    </div>

    <div class="nav-right">
        <span class="welcome">Welcome, Student</span>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<!-- CENTERED DASHBOARD -->
<section class="dashboard-wrapper">
    <div class="dashboard">
        <h2>Student Dashboard</h2>
        <p class="subtitle">Manage your placement activities</p>

        <div class="card-container">

            <!-- Upload Resume -->
            <div class="card">
                <h3>Upload Resume</h3>
                <p>Upload your CV securely using the official Google Form.</p>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSeo1Nis-wQxs2VcJtSMnfU96co4QAbxl6kwBpe9gk0fLpMiww/viewform" target="_blank" class="card-btn">
                    Upload CV
                </a>
            </div>

            <!-- Test -->
            <div class="card">
                <h3>Eligibility Test</h3>
                <p>Check placement eligibility.</p>
                <a href="test.php" class="card-btn">Take Test</a>
            </div>

            <!-- Placements -->
            <div class="card">
                <h3>Placements</h3>
                <p>View available job opportunities.</p>
                <a href="placements.php" class="card-btn">View Placements</a>
            </div>

        </div>
    </div>
</section>

</body>
</html>
