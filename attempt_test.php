<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

$student_id = $_SESSION['id'];
$test_id = $_GET['test_id'] ?? 0;

if ($test_id == 0) {
    echo "Invalid test";
    exit;
}

/* Prevent multiple attempts */
$check = mysqli_query($conn,"
SELECT id FROM test_attempts
WHERE student_id=$student_id AND test_id=$test_id
");

if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn,"
    INSERT INTO test_attempts (student_id, test_id)
    VALUES ($student_id, $test_id)
    ");
}

/* Redirect to test link */
$t = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT test_link FROM tests WHERE id=$test_id")
);

header("Location: ".$t['test_link']);
exit;
