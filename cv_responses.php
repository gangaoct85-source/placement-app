<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CV Responses | Admin</title>
    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f4f6fb;
        }
        .header {
            background: #2a0a5e;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
        }
        iframe {
            width: 100%;
            height: 90vh;
            border: none;
        }
    </style>
</head>
<body>

<div class="header">
    CV Upload Responses – Campus Placement
</div>

<iframe src="https://docs.google.com/spreadsheets/d/1s2G0S5oZ8hKO7uTTFqy92PjBy_TgciS2_IEhvuJ1CxM/edit?usp=sharing"></iframe>

</body>
</html>
