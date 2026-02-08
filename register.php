<?php
session_start();
include 'config/db.php';

$error = "";

/* Fetch departments */
$deptResult = mysqli_query($conn, "SELECT * FROM departments");

if (isset($_POST['register'])) {

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];
    $role  = $_POST['role'];
    $department_id = ($role == 'student') ? $_POST['department_id'] : NULL;

    if (!preg_match("/@gvncollege\.edu\.in$/", $email)) {
        $error = "Only @gvncollege.edu.in email is allowed";
    }
    elseif ($role == 'student' && empty($department_id)) {
        $error = "Please select your department";
    }
    else {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email already registered";
        } else {

            mysqli_query($conn,"INSERT INTO users
            (name,email,password,role,department_id,otp,otp_status)
            VALUES
            ('$name','$email','$pass','$role','$department_id','', 'pending')");

            header("Location: verify.php?email=$email");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>GVN College Register</title>
<link rel="stylesheet" href="assets/login.css">
</head>
<body>

<div class="login-container">
<div class="login-right">

<h2>Create Account</h2>
<p style="color:red;"><?php echo $error; ?></p>

<form method="post">

<label>Name</label>
<input type="text" name="name" required>

<label>Role</label>
<select name="role" id="role" required>
    <option value="">Select</option>
    <option value="student">Student</option>
    <option value="admin">Admin</option>
</select>

<label id="deptLabel" style="display:none;">Department</label>
<select name="department_id" id="department" style="display:none;">
    <option value="">Select Department</option>
    <?php while($d=mysqli_fetch_assoc($deptResult)){ ?>
        <option value="<?php echo $d['id']; ?>">
            <?php echo $d['department_name']; ?>
        </option>
    <?php } ?>
</select>

<label>Email</label>
<input type="email" name="email" required placeholder="name@gvncollege.edu.in">

<label>Password</label>
<input type="password" name="password" required>

<button name="register">Register</button>

</form>
</div>
</div>

<script>
document.getElementById("role").addEventListener("change",function(){
    if(this.value=="student"){
        document.getElementById("department").style.display="block";
        document.getElementById("deptLabel").style.display="block";
        document.getElementById("department").required = true;
    } else {
        document.getElementById("department").style.display="none";
        document.getElementById("deptLabel").style.display="none";
        document.getElementById("department").required = false;
    }
});
</script>

</body>
</html>
