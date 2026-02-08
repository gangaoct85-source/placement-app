<?php
session_start(); include '../config/db.php';
$sid=$_SESSION['id'];
if(isset($_POST['upload'])){
$f=$_FILES['cv']['name'];
move_uploaded_file($_FILES['cv']['tmp_name'],"../uploads/cvs/".$f);
mysqli_query($conn,"INSERT INTO cvs(student_id,cv_file) VALUES('$sid','$f')");
echo "Uploaded";
}
?>
<form method="post" enctype="multipart/form-data">
<input type="file" name="cv"><br>
<button name="upload">Upload CV</button>
</form>