<?php
session_start();
include '../config/db.php';

/* Admin protection */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

/* Delete student */
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id=$delete_id AND role='student'");
    header("Location: students.php");
    exit;
}

/* Fetch departments */
$departments = mysqli_query($conn, "SELECT * FROM departments");

/* Filter department */
$dept_filter = isset($_GET['dept']) ? intval($_GET['dept']) : 0;

$where = "WHERE users.role='student'";
if ($dept_filter > 0) {
    $where .= " AND users.department_id = $dept_filter";
}

/* Fetch students */
$query = "
SELECT 
    users.id,
    users.name,
    users.email,
    departments.department_name
FROM users
LEFT JOIN departments ON users.department_id = departments.id
$where
ORDER BY users.name
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Students Management</title>

<style>
body{
    font-family:Segoe UI, sans-serif;
    background:#f4f6fb;
    margin:0;
}

/* HEADER */
.header{
    background:#5f2c82;
    color:#fff;
    padding:25px 30px;
    position:relative;
}
.header h1{margin:0;}
.header p{margin:6px 0 0;opacity:.9}

/* DASHBOARD BUTTON */
.dashboard-btn{
    position:absolute;
    right:30px;
    top:25px;
    background:#ffffff;
    color:#5f2c82;
    padding:10px 18px;
    border-radius:25px;
    text-decoration:none;
    font-weight:600;
    box-shadow:0 5px 15px rgba(0,0,0,.2);
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

/* DEPARTMENT FILTER */
.dept-bar a{
    padding:10px 18px;
    margin:6px;
    display:inline-block;
    border-radius:25px;
    background:#fff;
    text-decoration:none;
    color:#5f2c82;
    font-weight:600;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
}
.dept-bar a.active{
    background:#5f2c82;
    color:#fff;
}

/* TABLE */
table{
    width:100%;
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}
th,td{padding:14px}
th{background:#5f2c82;color:#fff}
tr:nth-child(even){background:#f1f3f8}

/* BADGE */
.badge{
    background:#49a09d;
    color:#fff;
    padding:6px 12px;
    border-radius:20px;
}

/* DELETE BUTTON */
.delete{
    color:#fff;
    background:#e74c3c;
    padding:6px 14px;
    border-radius:20px;
    text-decoration:none;
}
.delete:hover{background:#c0392b}
</style>

</head>
<body>

<div class="header">
    <h1>Students Management</h1>
    <p>Filter by department & manage students</p>

    <a href="dashboard.php" class="dashboard-btn">🏠 Dashboard</a>
</div>

<div class="container">

    <!-- DEPARTMENT FILTER -->
    <div class="dept-bar">
        <a href="students.php" class="<?= ($dept_filter==0)?'active':'' ?>">All</a>
        <?php while($d = mysqli_fetch_assoc($departments)): ?>
            <a href="students.php?dept=<?= $d['id'] ?>"
               class="<?= ($dept_filter==$d['id'])?'active':'' ?>">
                <?= htmlspecialchars($d['department_name']) ?>
            </a>
        <?php endwhile; ?>
    </div>

    <br>

    <!-- STUDENTS TABLE -->
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Department</th>
            <th>Action</th>
        </tr>

        <?php if(mysqli_num_rows($result)>0): ?>
            <?php while($row=mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>
                        <span class="badge">
                            <?= htmlspecialchars($row['department_name'] ?? 'Not Assigned') ?>
                        </span>
                    </td>
                    <td>
                        <a class="delete"
                           href="students.php?delete=<?= $row['id'] ?>"
                           onclick="return confirm('Are you sure you want to delete this student?')">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align:center;">No students found</td>
            </tr>
        <?php endif; ?>
    </table>

</div>

</body>
</html>
