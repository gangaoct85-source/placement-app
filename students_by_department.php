<?php
include '../includes/db_connect.php';

$dept_id = $_GET['dept_id'];

$query = "
SELECT students.name, students.email, departments.department_name
FROM students
JOIN departments ON students.department_id = departments.id
WHERE departments.id = '$dept_id'
";

$result = mysqli_query($conn, $query);
?>

<h2>Students List</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Department</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['name']; ?></td>
    <td><?= $row['email']; ?></td>
    <td><?= $row['department_name']; ?></td>
</tr>
<?php } ?>
</table>
