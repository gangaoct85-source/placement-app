<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$dept_id = $_SESSION['department_id'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Available Tests</title>
<style>
body{font-family:Segoe UI;background:#f4f6fb;margin:0}
.header{background:#5f2c82;color:#fff;padding:20px}
.container{width:80%;margin:30px auto}
.card{
    background:#fff;
    padding:20px;
    border-radius:12px;
    margin-bottom:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.1)
}
.card h3{margin:0;color:#5f2c82}
.card a{
    display:inline-block;
    margin-top:10px;
    padding:8px 18px;
    background:#49a09d;
    color:#fff;
    border-radius:20px;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="header">
    <h2>Available Tests</h2>
</div>

<div class="container">

<?php
$result = mysqli_query($conn,"
SELECT tests.id, tests.test_name
FROM tests
JOIN test_departments ON tests.id = test_departments.test_id
WHERE test_departments.department_id = $dept_id
");

if (mysqli_num_rows($result) > 0):
    while($row = mysqli_fetch_assoc($result)):
?>
    <div class="card">
        <h3><?= htmlspecialchars($row['test_name']) ?></h3>
        <a href="attempt_test.php?test_id=<?= $row['id'] ?>">Start Test</a>
    </div>
<?php
    endwhile;
else:
?>
    <p>No tests assigned to your department.</p>
<?php endif; ?>

</div>

</body>
</html>
