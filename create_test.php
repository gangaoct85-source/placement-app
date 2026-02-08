<?php
session_start();
include '../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$departments = mysqli_query($conn,"SELECT * FROM departments");

if(isset($_POST['create_test'])){
    $name = $_POST['test_name'];
    $link = $_POST['test_link'];
    $depts = $_POST['departments'];

    mysqli_query($conn,"INSERT INTO tests (test_name,test_link) VALUES ('$name','$link')");
    $test_id = mysqli_insert_id($conn);

    foreach($depts as $d){
        mysqli_query($conn,"INSERT INTO test_departments VALUES ($test_id,$d)");
    }
    $success = "Test created successfully";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create Test</title>
<style>
body{font-family:Segoe UI;background:#f4f6fb}
.box{width:500px;margin:40px auto;background:#fff;padding:25px;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.1)}
h2{text-align:center;color:#5f2c82}
label{font-weight:600}
input{width:100%;padding:10px;margin:10px 0;border-radius:8px;border:1px solid #ccc}
.depts label{display:block;margin:6px 0}
button{width:100%;padding:12px;background:#5f2c82;color:#fff;border:none;border-radius:8px}
</style>
</head>
<body>

<div class="box">
<h2>Create Test</h2>
<?php if(isset($success)) echo "<p style='color:green'>$success</p>"; ?>

<form method="post">
<label>Test Name</label>
<input type="text" name="test_name" required>

<label>Test Link</label>
<input type="url" name="test_link" required>

<label>Departments</label>
<div class="depts">
<?php while($d=mysqli_fetch_assoc($departments)): ?>
<label><input type="checkbox" name="departments[]" value="<?= $d['id'] ?>"> <?= $d['department_name'] ?></label>
<?php endwhile; ?>
</div>

<button name="create_test">Publish Test</button>
</form>
</div>

</body>
</html>
