<?php
session_start();
include 'config/db.php';

$error = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $pass  = $_POST['password'];
    $role  = $_POST['role'];

    // Allow only college email
    if (!preg_match("/@gvncollege\.edu\.in$/", $email)) {
        $error = "Only @gvncollege.edu.in email is allowed";
    } 
    else {

        $q = mysqli_query($conn,
    "SELECT * FROM users 
     WHERE email='$email' 
     AND password='$pass' 
     AND role='$role'"
);


        if (mysqli_num_rows($q) == 1) {

            $row = mysqli_fetch_assoc($q);

            // SET SESSION
            $_SESSION['department_id'] = $row['department_id'];
            $_SESSION['id']         = $row['id'];
            $_SESSION['role']       = $row['role'];
            $_SESSION['department'] = $row['department'];
            $_SESSION['year']       = $row['year'];
            $_SESSION['batch']      = $row['batch'];


            // Redirect
            if ($row['role'] == "admin") {
                header("Location: admin/dashboard.php");
                exit();
            } else {
                header("Location: student/dashboard.php");
                exit();
            }

        } else {
            $error = "Invalid login OR Email not verified";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GVN College | Campus Placement Login</title>
    <link rel="stylesheet" href="assets/login.css">
</head>
<body>

<div class="login-container">

    <!-- LEFT : COLLEGE IMAGE -->
    <div class="login-left">
        <div class="overlay">
            <h1>G. Venkatasamy Naidu College</h1>
            <p>Kovilpatti<br>Campus Placement Portal</p>
        </div>
    </div>

    <!-- RIGHT : LOGIN FORM -->
    <div class="login-right">
        <h2>Sign In</h2>

        <?php if($error!=""): ?>
            <p style="color:red; margin-bottom:15px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post">

            <label>Login As</label>
            <select name="role" required>
                <option value="">-- Select Role --</option>
                <option value="student">Student</option>
                <option value="admin">Admin</option>
            </select>


            <label>Email</label>
            <input type="email"
                   name="email"
                   required
                   placeholder="name@gvncollege.edu.in">

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" name="login">Sign In</button>
        </form>

        <p class="note">
            New user?
            <a href="register.php">Create an account</a>
        </p>

    </div>

</div>
/* FORCE dropdowns to show */
select, option {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    background: white !important;
    color: black !important;
}


</body>
</html>

