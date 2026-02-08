<?php
session_start();
include '../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

/* Departments */
$departments = mysqli_query($conn,"SELECT * FROM departments");

/* Selected department */
$dept_id = $_GET['dept'] ?? 0;

/* Fetch students who attended */
$students = [];
if ($dept_id > 0) {
    $students = mysqli_query($conn,"
        SELECT DISTINCT users.id, users.name, users.email
        FROM users
        JOIN test_attempts ON users.id = test_attempts.student_id
        WHERE users.department_id = $dept_id
    ");
}

/* Function to calculate score */
function getScore($conn,$student_id,$test_id){
    $q = mysqli_query($conn,"
        SELECT COUNT(*) AS score
        FROM student_answers sa
        JOIN test_questions tq ON sa.question_id = tq.id
        WHERE sa.student_id=$student_id
        AND sa.student_answer = tq.correct_answer
        AND sa.test_id=$test_id
    ");
    return mysqli_fetch_assoc($q)['score'] ?? 0;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Result Analysis</title>

<style>
body{margin:0;font-family:Segoe UI;background:#f4f6fb}
.header{
    background:#5f2c82;
    color:#fff;
    padding:20px 30px;
    position:relative;
}
.header a{
    position:absolute;
    right:30px;
    top:20px;
    background:#fff;
    color:#5f2c82;
    padding:10px 18px;
    border-radius:25px;
    text-decoration:none;
    font-weight:600;
}
.layout{
    display:flex;
    height:calc(100vh - 80px);
}

/* LEFT */
.sidebar{
    width:260px;
    background:#fff;
    padding:20px;
    box-shadow:5px 0 15px rgba(0,0,0,.05);
}
.sidebar h3{margin-top:0}
.sidebar a{
    display:block;
    padding:10px;
    margin-bottom:8px;
    text-decoration:none;
    color:#333;
    border-radius:8px;
}
.sidebar a.active,
.sidebar a:hover{
    background:#5f2c82;
    color:#fff;
}

/* RIGHT */
.content{
    flex:1;
    padding:30px;
}
table{
    width:100%;
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
}
th,td{padding:14px}
th{background:#5f2c82;color:#fff}
tr:nth-child(even){background:#f1f3f8}
.badge{
    background:#49a09d;
    color:#fff;
    padding:6px 14px;
    border-radius:20px;
}
</style>
</head>

<body>

<div class="header">
    <h2>Result Analysis</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
</div>

<div class="layout">

    <!-- LEFT DEPARTMENTS -->
    <div class="sidebar">
        <h3>Departments</h3>
        <?php while($d=mysqli_fetch_assoc($departments)): ?>
            <a href="?dept=<?= $d['id'] ?>"
               class="<?= ($dept_id==$d['id'])?'active':'' ?>">
                <?= $d['department_name'] ?>
            </a>
        <?php endwhile; ?>
    </div>

    <!-- RIGHT RESULTS -->
    <div class="content">
        <?php if($dept_id==0): ?>
            <h3>Select a department to view results</h3>
        <?php else: ?>

        <h3>Attended Students</h3>

        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Correct Answers</th>
            </tr>

            <?php if(mysqli_num_rows($students)>0): ?>
                <?php while($s=mysqli_fetch_assoc($students)): ?>
                    <tr>
                        <td><?= $s['name'] ?></td>
                        <td><?= $s['email'] ?></td>
                        <td><span class="badge">Attended</span></td>
                        <td><?= getScore($conn,$s['id'],1) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center">No attempts</td>
                </tr>
            <?php endif; ?>
        </table>

        <?php endif; ?>
    </div>

</div>

</body>
</html>
