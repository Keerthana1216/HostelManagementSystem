<?php
session_start();
include('../includes/dbconn.php'); // Include database connection

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

// Check if student ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: view_students.php");
    exit();
}

$student_id = mysqli_real_escape_string($connection, $_GET['id']);

// Delete query
$delete_query = "DELETE FROM students WHERE sid = '$student_id'";
$delete_result = mysqli_query($connection, $delete_query);

if ($delete_result) {
    $_SESSION['success_message'] = "Student deleted successfully!";
} else {
    $_SESSION['error_message'] = "Error deleting record: " . mysqli_error($connection);
}

header("Location: view_students.php"); // Redirect to view_students.php
exit();
?>
