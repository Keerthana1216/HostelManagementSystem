<?php
session_start();
include('../includes/dbconn.php'); // Include database connection

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

// Check if teacher ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid Teacher ID!";
    header("Location: view_teachers.php");
    exit();
}

$teacher_id = mysqli_real_escape_string($connection, $_GET['id']);

// Fetch teacher details
$query = "SELECT * FROM teachers WHERE rid = '$teacher_id'";
$result = mysqli_query($connection, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    $_SESSION['error_message'] = "Faculty not found!";
    header("Location: view_teachers.php");
    exit();
}

$teacher = mysqli_fetch_assoc($result);

// Handle form submission
if (isset($_POST['update_teacher'])) {
    $rid = mysqli_real_escape_string($connection, $_POST['rid']);
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $gender = mysqli_real_escape_string($connection, $_POST['gender']);
    $mobile = mysqli_real_escape_string($connection, $_POST['mobile']);

    // Update teacher details in the database
    $update_query = "UPDATE teachers SET rid='$rid', name='$name', email='$email', gender='$gender', mobile='$mobile' WHERE rid='$teacher_id'";
    
    if (mysqli_query($connection, $update_query)) {
        $_SESSION['success_message'] = "Faculty details updated successfully!";
        header("Location: view_teachers.php"); // Redirect to add_teacher.php after update
        exit();
    } else {
        $_SESSION['error_message'] = "Error updating record.. " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>HMS</title>
</head>
<body style="background-color: #f8f9fa;">
    <style>
        @media (max-width: 768px) {
    .sidebar {
        width: 100%;  /* Full width on small screens */
        height: auto;  /* Adjust height */
        position: relative;
    }
    .main-content {
        margin-left: 0;  /* Remove left margin */
        width: 100%;  /* Take full width */
        padding: 10px;  /* Add spacing */
    }
}</style>

<?php include('sidebar.php'); ?>
<br>
<div class="main-content" style="margin-left: 260px; padding: 20px;">
    <div class="">
        <h3>Update Faculty</h3>
        <hr>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="rid">RID</label>
                <input type="text" class="form-control" name="rid" value="<?= $teacher['rid']; ?>" required>
            </div>

            <div class="form-group">
                <label for="name">Faculty Name</label>
                <input type="text" class="form-control" name="name" value="<?= $teacher['name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email ID</label>
                <input type="email" class="form-control" name="email" value="<?= $teacher['email']; ?>" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" name="gender" required>
                    <option value="Male" <?= ($teacher['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?= ($teacher['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                    <option value="Others" <?= ($teacher['gender'] == 'Others') ? 'selected' : ''; ?>>Others</option>
                </select>
            </div>

            <div class="form-group">
                <label for="mobile">Contact No</label>
                <input type="tel" class="form-control" name="mobile" value="<?= $teacher['mobile']; ?>" pattern="[0-9]{10}" required>
            </div>
<br>
            <center>
                <button type="submit" class="btn btn-primary" name="update_teacher">Update</button>
                <a href="view_teachers.php" class="btn btn-secondary">Cancel</a>
            </center>
        </form>
    </div>
</div>
<script>
      function toggleSidebar() {
    let sidebar = document.querySelector('.sidebar');
    let hmsTitle = document.getElementById('hms-title');
    let mainContent = document.querySelectorAll('.main-content');

    sidebar.classList.toggle('closed');

    if (sidebar.classList.contains('closed')) {
        hmsTitle.style.display = 'none';
        mainContent.forEach(el => el.classList.add('expanded'));
    } else {
        hmsTitle.style.display = 'block';
        mainContent.forEach(el => el.classList.remove('expanded'));
    }
}</script>
</body>
</html>
