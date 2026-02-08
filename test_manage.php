<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$departments = mysqli_query($conn, "SELECT * FROM departments");

/* Create Test */
if (isset($_POST['create_test'])) {
    $test_name = $_POST['test_name'];
    $test_link = $_POST['test_link'];
    $depts = $_POST['departments'] ?? [];

    mysqli_query($conn,
        "INSERT INTO tests (test_name, test_link)
         VALUES ('$test_name','$test_link')"
    );
    $test_id = mysqli_insert_id($conn);

    foreach ($depts as $d) {
        mysqli_query($conn,
            "INSERT INTO test_departments (test_id, department_id)
             VALUES ($test_id, $d)"
        );
    }

    $success = "Test created successfully";
}

/* Analysis */
$total_tests = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM tests")
)[0];

$total_attempts = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM test_attempts")
)[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Tests</title>

<style>
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:#f4f6fb;
}

/* HEADER */
.header{
    background:#5f2c82;
    color:#fff;
    padding:25px 30px;
    position:relative;
}
.header h1{margin:0}
.header p{margin:6px 0 0;opacity:.9}

/* DASHBOARD BUTTON */
.dashboard-btn{
    position:absolute;
    right:30px;
    top:25px;
    background:#fff;
    color:#5f2c82;
    padding:10px 18px;
    border-radius:25px;
    text-decoration:none;
    font-weight:600;
}
.dashboard-btn:hover{
    background:#49a09d;
    color:#fff;
}

/* CONTAINER */
.container{
    width:90%;
    margin:30px auto;
}

/* STATS */
.cards{
    display:grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}
.card{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
}
.card h2{margin:0;color:#5f2c82}
.card span{font-size:36px;font-weight:bold}

/* FORM */
.form-box{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
    max-width:600px;
    margin:auto;
}
label{font-weight:600}
input[type=text], input[type=url]{
    width:100%;
    padding:12px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ccc;
}

/* DEPARTMENT LIST – FIXED ALIGNMENT */
.dept-list{
    margin:15px 0;
}
.dept-item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:8px 0;
}
.dept-item input{
    width:16px;
    height:16px;
}

/* BUTTON */
button{
    width:100%;
    padding:14px;
    background:#5f2c82;
    color:#fff;
    border:none;
    border-radius:8px;
    font-size:16px;
}
button:hover{background:#49a09d}
</style>

</head>
<body>

<div class="header">
    <h1>Manage Tests</h1>
    <p>Create tests & assign departments</p>
    <a href="dashboard.php" class="dashboard-btn">🏠 Dashboard</a>
</div>

<div class="container">

    <!-- ANALYSIS CARDS -->
    <div class="cards">
        <div class="card">
            <h2>Total Tests</h2>
            <span><?= $total_tests ?></span>
        </div>
        <div class="card">
            <h2>Total Attempts</h2>
            <span><?= $total_attempts ?></span>
        </div>
    </div>

    <!-- CREATE TEST -->
    <div class="form-box">
        <h2>Create New Test</h2>

        <?php if(isset($success)): ?>
            <p style="color:green"><?= $success ?></p>
        <?php endif; ?>

        <form method="post">
            <label>Test Name</label>
            <input type="text" name="test_name" required>

            <label>Test Link (Google Form)</label>
            <input type="url" name="test_link" required>

            <label>Departments</label>
            <div class="dept-list">
                <?php while($d = mysqli_fetch_assoc($departments)): ?>
                    <div class="dept-item">
                        <input type="checkbox" name="departments[]" value="<?= $d['id'] ?>">
                        <span><?= htmlspecialchars($d['department_name']) ?></span>
                    </div>
                <?php endwhile; ?>
            </div>

            <button name="create_test">Publish Test</button>
        </form>
    </div>

</div>

</body>
</html>
