<?php
session_start();
include('../includes/dbconn.php'); // Ensure this correctly connects to MySQL

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

// Handling form submission
if (isset($_POST['add_teacher'])) {
    // Get and sanitize input
    $tid = mysqli_real_escape_string($connection, $_POST['tid']);
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing for security
    $gender = mysqli_real_escape_string($connection, $_POST['gender']);
    $mobile = mysqli_real_escape_string($connection, $_POST['mobile']);

    // Validate dropdown selection
    if ($gender == "Select") {
        $_SESSION['error_message'] = "Please select a valid gender.";
        header("Location: add_teacher.php");
        exit();
    }

    // Insert teacher into the database
    $query = "INSERT INTO teachers (tid, name, email, password, gender, mobile) 
              VALUES ('$tid', '$name', '$email', '$password', '$gender', '$mobile')";

    if (mysqli_query($connection, $query)) {
        $_SESSION['success_message'] = "Faculty added successfully!";
        header("Location: view_teachers.php"); // Redirect to view_teachers.php
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . mysqli_error($connection);
        header("Location: add_teacher.php");
        exit();
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
    <script>
        // Show alert messages
        window.onload = function() {
            <?php if (isset($_SESSION['success_message'])) { ?>
                alert("<?php echo $_SESSION['success_message']; ?>");
                <?php unset($_SESSION['success_message']); ?>
            <?php } ?>
            
            <?php if (isset($_SESSION['error_message'])) { ?>
                alert("<?php echo $_SESSION['error_message']; ?>");
                <?php unset($_SESSION['error_message']); ?>
            <?php } ?>
        };
    </script>
</head>
<body style="background-color: #f8f9fa;">
<style>
.main-content {
    margin-left: 260px; /* Same as sidebar width */
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
}
</style>
<?php include('sidebar.php'); ?>
<br>
<!-- Main Content -->
<div class="main-content">
    <div class="">
        <h3>Add New Faculty</h3>
        <hr>
        <form method="POST" action="add_teacher.php">
            <div class="form-group">
                <label for="tid">Faculty ID</label>
                <input type="text" class="form-control" name="tid" required>
            </div>

            <div class="form-group">
                <label for="name">Faculty Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email ID</label>
                <input type="email" class="form-control" name="email" required>
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

            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" name="gender" required>
                    <option value="Select">--Select--</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <div class="form-group">
                <label for="mobile">Contact No</label>
                <input type="tel" class="form-control" name="mobile" pattern="[0-9]{10}" required>
            </div>
            <br>
            <center><button type="submit" class="btn btn-primary" name="add_teacher">Add faculty</button></center>
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
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
