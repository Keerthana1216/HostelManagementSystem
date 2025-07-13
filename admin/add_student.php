<?php
session_start();
include('../includes/dbconn.php'); // Ensure this correctly connects to MySQL

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

// Handling form submission
if (isset($_POST['add_student'])) {
    // Get and sanitize input
    $sid = mysqli_real_escape_string($connection, $_POST['sid']);
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $st_id = mysqli_real_escape_string($connection, $_POST['st_id']);
    $department = mysqli_real_escape_string($connection, $_POST['department']);
    $joining_year = mysqli_real_escape_string($connection, $_POST['joining_year']);
    $current_year = mysqli_real_escape_string($connection, $_POST['current_year']);
    $passout_year = mysqli_real_escape_string($connection, $_POST['passout_year']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $gender = mysqli_real_escape_string($connection, $_POST['gender']);
    $mobile = mysqli_real_escape_string($connection, $_POST['mobile']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // Insert student into the database
    $query = "INSERT INTO students (sid, name, email, st_id, department, joining_year, current_year, passout_year, address, gender, mobile, password) 
              VALUES ('$sid', '$name', '$email', '$st_id','$department', '$joining_year', '$current_year', '$passout_year', '$address', '$gender', '$mobile', '$password')";

    if (mysqli_query($connection, $query)) {
        $_SESSION['success_message'] = "Student added successfully!";
        header("Location: view_students.php"); // Redirect to view_students.php
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . mysqli_error($connection);
        header("Location: add_student.php");
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
}
</style>
<?php include('sidebar.php'); ?>
<br>
<!-- Main Content -->
<div class="main-content">
    <div class="">
        <h3>Add New Student</h3>
        <hr>

        <form method="POST" action="add_student.php">
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sid">SID</label>
                        <input type="text" class="form-control" name="sid" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Student Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email ID</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="st_id">Reg_No</label>
                        <input type="number" class="form-control" name="st_id" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select class="form-control" name="department" required>
                            <option value="Select">--Select--</option>
                            <option value="CSE">CSE</option>
                            <option value="ECE">ECE</option>
                            <option value="EEE">EEE</option>
                            <option value="MECH">MECH</option>
                            <option value="CIVIL">CIVIL</option>
                            <option value="AI&DS">AI&DS</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="current_year">Year</label>
                        <select class="form-control" name="current_year" required>
                            <option value="Select">--Select--</option>
                            <option value="I">I</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
            <div class="col-md-6">
                    <div class="form-group">
                        <label for="joining_year">Batch</label>
                        <input type="number" class="form-control" name="joining_year" min="2021" max="2050" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="passout_year">Passout Year</label>
                        <input type="number" class="form-control" name="passout_year" min="2021" max="2050" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" name="address" rows="4" required></textarea>
            </div>
            <div class="form-row">
            <div class="col-md-6">
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control" name="gender" required>
                            <option value="Select">--Select--</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                </div>
            <div class="col-md-6">
                   <div class="form-group">
                       <label for="mobile">Contact No</label>
                       <input type="tel" class="form-control" name="mobile" pattern="[0-9]{10}" required>
                   </div>
           </div>
           <div class="col-md-6">
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
        </form>
        <br>
        <center><button type="submit" class="btn btn-primary" name="add_student">Add Student</button></center>
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
