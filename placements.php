<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

$dept_id = $_SESSION['department_id'];   // correct department
$today = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Available Placements</title>
<style>
body{
    font-family: Arial, sans-serif;
    background:#eef2f5;
    margin:0;
}
.card{
    background:white;
    padding:20px;
    margin:15px auto;
    width:70%;
    border-radius:10px;
    box-shadow:0 0 10px #ccc;
}
h2{
    text-align:center;
    color:#007bff;
}
.back{
    margin:20px;
    text-align:center;
}
</style>
</head>

<body>

<h2>Available Placements</h2>

<?php
$q = mysqli_query($conn,"
SELECT p.* 
FROM placements p
JOIN departments d ON p.department = d.department_name
WHERE d.id = '$dept_id'
AND p.expiry_date >= '$today'
");

if(mysqli_num_rows($q)==0){
    echo "<p style='text-align:center;'>No placements available for your department.</p>";
}

while($p = mysqli_fetch_assoc($q)){
    echo "<div class='card'>
        <b>{$p['company_name']}</b><br>
        Role: {$p['job_role']}<br>
        Package: {$p['package']}<br>
        Last Date: {$p['expiry_date']}
    </div>";
}
?>

<div class="back">
    <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>
