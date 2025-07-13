<?php 
session_start();
if(isset($_POST['login'])){
    include('../includes/dbconn.php');
    $query = "SELECT rid, email, password, name FROM teachers WHERE email = '$_POST[email]' AND password = '$_POST[password]'";
    $query_run = mysqli_query($connection,$query);
    if(mysqli_num_rows($query_run)){
        $_SESSION['email'] = $_POST['email'];
        while($row = mysqli_fetch_assoc($query_run)){
            $_SESSION['name'] = $row['name'];
            $_SESSION['rid'] = $row['rid'];
        }
        echo "<script type='text/javascript'>
          window.location.href = 'dashboard.php';
        </script>";
    }
    else{
      echo "<script type='text/javascript'>
          alert('Please Enter Valid Email and Password..');
          window.location.href = 'index1.php';
      </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>HMS</title>
    <style>
        /*Background Image Styling */
        body {
            background: url('../images/2.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        /* Login Form Styling */
        .login-container {
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        button[name="login"] {
            background-color: rgb(0, 123, 255);
            color: white;
            border: 2px solid #4a4a4a;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button[name="login"]:hover {
            background-color: #5a5a6e;
            border-color: #343a40;
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
        h3 {
    color: white; /* Change title text to white */
    font-weight: bold; /* Make it bold */
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7); /* Add shadow for better visibility */
}
    </style>
</head>
<br>
<body style="background-color: #FBF6EE;">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <center><b><h3>HOSTEL MANAGEMENT SYSTEM</h3></b></center>
            <br><br>
            <div class="card login-container">
                <div class="card-header">
                    <center><b>Faculty Login</b></center>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="email">Email ID</label>
                            <input type="text" class="form-control" id="username" name="email" placeholder="Enter your email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                              <input type="password" class="form-control" id="password" placeholder="Enter your password" name="password" required>
                              <div class="input-group-append">
                            <span class="input-group-text password-toggle" onclick="togglePassword()" title="Show/Hide Password">
                                <i id="eye-icon" class="fa fa-eye"></i>
                            </span>
                        </div>
                       </div>
                    </div>
                        <br>
                        <center>
                            <button type="submit" name="login">Login</button>
                        </center>                       
                        <br>
                        <center><span>Haven't registered yet? <a href="register.php">Register Here</a></span></center>                      
                        <div class="form-footer text-center mt-3">
                             <p class="text-muted"><a href="../index.php"><i class="ti-arrow-left"></i>Go Back</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function togglePassword() {
    var passwordField = document.getElementById("password");
    var eyeIcon = document.getElementById("eye-icon");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash"); // Changes icon to "eye-slash"
    } else {
        passwordField.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye"); // Changes icon back to "eye"
    }
}
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
