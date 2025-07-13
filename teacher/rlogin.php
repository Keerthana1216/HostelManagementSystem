<?php
session_start();
include('../includes/dbconn.php'); // Make sure this defines $mysqli

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $mysqli->prepare("SELECT * FROM faculty WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        $_SESSION['rid'] = $row['rid'];

        header("Location: update_faculty_status.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .login-box {
            margin-top: 100px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        .card {
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<?php include('topbar.php'); ?>
<div class="login-box">
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card card-custom text-center bg-light">
            <div class="card-body">
                <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                <h4 class="text-success">Faculty Approval Site</h4>
                <p><b>Date:</b> <?php echo date("F j, Y"); ?></p>
            </div>
        </div>
    </div>
    </div>
</div>
</body>
</html>
