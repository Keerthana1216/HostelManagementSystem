<?php
session_start();
include('../includes/dbconn.php');

if (!isset($_SESSION['email'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $day = $_POST['day'];
    $meal = $_POST['meal_type'];
    $menu = trim($_POST['menu']);

    // Check if the meal already exists
    $check_sql = "SELECT id FROM food_schedule WHERE day = ? AND meal_type = ?";
    $check_stmt = $connection->prepare($check_sql);
    $check_stmt->bind_param("ss", $day, $meal);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Meal already exists for this day"]);
        exit();
    }

    // Insert new meal
    $insert_sql = "INSERT INTO food_schedule (day, meal_type, menu) VALUES (?, ?, ?)";
    $insert_stmt = $connection->prepare($insert_sql);
    $insert_stmt->bind_param("sss", $day, $meal, $menu);
    $insert_stmt->execute();

    echo json_encode(["success" => true]);
    exit();
}
?>
