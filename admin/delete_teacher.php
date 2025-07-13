<?php
session_start();
include('../includes/dbconn.php'); // Include database connection

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

// Check if teacher ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid Faculty ID!";
    header("Location: view_teachers.php");
    exit();
}

$teacher_id = mysqli_real_escape_string($connection, $_GET['id']);

// Delete teacher from the database
$query = "DELETE FROM teachers WHERE tid = '$teacher_id'";
if (mysqli_query($connection, $query)) {
    $_SESSION['success_message'] = "Faculty deleted successfully!";
} else {
    $_SESSION['error_message'] = "Error: " . mysqli_error($connection);
}

// Redirect to view_teachers.php
header("Location: view_teachers.php");
exit();
?>
