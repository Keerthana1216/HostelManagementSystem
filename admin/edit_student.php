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

$sid = mysqli_real_escape_string($connection, $_GET['id']);

// Fetch student details
$query = "SELECT * FROM students WHERE sid = '$sid'";
$query_run = mysqli_query($connection, $query);

if (!$query_run || mysqli_num_rows($query_run) == 0) {
    $_SESSION['error_message'] = "Student not found.";
    header("Location: view_students.php");
    exit();
}

$student = mysqli_fetch_assoc($query_run);

// Handle update request
if (isset($_POST['update_student'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $st_id = mysqli_real_escape_string($connection, $_POST['st_id']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $department = mysqli_real_escape_string($connection, $_POST['department']);
    $joining_year = mysqli_real_escape_string($connection, $_POST['joining_year']);
    $current_year = mysqli_real_escape_string($connection, $_POST['current_year']);
    $passout_year = mysqli_real_escape_string($connection, $_POST['passout_year']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $gender = mysqli_real_escape_string($connection, $_POST['gender']);
    $mobile = mysqli_real_escape_string($connection, $_POST['mobile']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    // Update query
    $update_query = "UPDATE students SET 
                     name='$name', st_id='$st_id', email='$email', department='$department', 
                     joining_year='$joining_year', current_year='$current_year', 
                     passout_year='$passout_year', address='$address', gender='$gender', 
                     mobile='$mobile', password='$password'
                     WHERE sid='$sid'";

    if (mysqli_query($connection, $update_query)) {
        $_SESSION['success_message'] = "Student details updated successfully!";
        header("Location: view_students.php"); // Redirect to add_student.php
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
/* Default main content when sidebar is open */
.main-content {
    margin-left: 250px;
    padding: 20px;
    transition: all 0.3s ease;
}

/* When sidebar is collapsed */
.sidebar-collapsed .main-content {
    margin-left: 0;
}

/* For mobile view - sidebar auto collapses */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0 !important;
    }
}

        .main-content {
    margin-left: 260px; /* Same as sidebar width + margin */
    padding: 20px;
    transition: margin-left 0.3s;
}
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
        <h3>Update Student</h3>
        <hr>

        <form method="POST">
            <div class="form-group">
                <label for="sid">SID</label>
                <input type="text" class="form-control" name="sid" value="<?= $student['sid'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="name">Student Name</label>
                <input type="text" class="form-control" name="name" value="<?= $student['name'] ?>" required>
            </div>
            <div class="form-group">
                <label for="st_id">Reg.No</label>
                <input type="text" class="form-control" name="st_id" value="<?= $student['st_id'] ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email ID</label>
                <input type="email" class="form-control" name="email" value="<?= $student['email'] ?>" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <select class="form-control" name="department" required>
                    <option value="<?= $student['department'] ?>" selected><?= $student['department'] ?></option>
                    <option value="CSE">CSE</option>
                    <option value="ECE">ECE</option>
                    <option value="EEE">EEE</option>
                    <option value="MECH">MECH</option>
                    <option value="CIVIL">CIVIL</option>
                    <option value="AI&DS">AI&DS</option>
                </select>
            </div>
            <div class="form-group">
                <label for="joining_year">Batch</label>
                <input type="number" class="form-control" name="joining_year" value="<?= $student['joining_year'] ?>" required>
            </div>
            <div class="form-group">
                <label for="current_year">Year</label>
                <select class="form-control" name="current_year" required>
                    <option value="<?= $student['current_year'] ?>" selected><?= $student['current_year'] ?></option>
                    <option value="I">I</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                    <option value="IV">IV</option>
                </select>
            </div>
            <div class="form-group">
                <label for="passout_year">Passout Year</label>
                <input type="number" class="form-control" name="passout_year" value="<?= $student['passout_year'] ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" name="address" rows="4" required><?= $student['address'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" name="gender" required>
                    <option value="<?= $student['gender'] ?>" selected><?= $student['gender'] ?></option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                </select>
            </div>
            <div class="form-group">
                <label for="mobile">Contact No</label>
                <input type="tel" class="form-control" name="mobile" value="<?= $student['mobile'] ?>" pattern="[0-9]{10}" required>
            </div>
            <div class="form-group">   
                    <label for="password">Set Password</label>
                    <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    Show
                   </button>
               </div>
            </div>  
        </div> 
            <center>
                <button type="submit" class="btn btn-primary" name="update_student">Update</button>
                <a href="view_students.php" class="btn btn-secondary">Cancel</a>
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
}
document.querySelector('.sidebar-toggle-btn').addEventListener('click', () => {
    document.body.classList.toggle('sidebar-collapsed');
});
    document.getElementById('togglePassword').addEventListener('click', function () {
        let passwordInput = document.getElementById('password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            this.textContent = 'Hide';
        } else {
            passwordInput.type = 'password';
            this.textContent = 'Show';
        }
    });
</script>
</body>
</html>
