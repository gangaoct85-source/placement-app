<?php
include '../includes/db_connect.php';
$departments = mysqli_query($conn, "SELECT * FROM departments");
?>

<h2>Select Department</h2>

<ul>
<?php while($dept = mysqli_fetch_assoc($departments)) { ?>
    <li>
        <a href="students_by_department.php?dept_id=<?= $dept['id']; ?>">
            <?= $dept['department_name']; ?>
        </a>
    </li>
<?php } ?>
</ul>
