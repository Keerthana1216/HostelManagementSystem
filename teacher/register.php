<?php 
session_start();
if(isset($_POST['register'])){
    include('../includes/dbconn.php');
    $query = "update teachers set password = '$_POST[password]' where email = '$_POST[email]'";
    $query_result = mysqli_query($connection,$query);
    if($query_result){
        echo "<script type='text/javascript'>
              alert('Registered Successfully..');
            window.location.href = 'index1.php';  
          </script>";
    }
    else{
        echo "<script type='text/javascript'>
              alert('Error..Plz try again.');
              window.location.href = 'register.php';
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
        
         button[name="register"] {
            background-color:rgb(0, 123, 255);
            color: white;
            border: 2px solid #4a4a4a;
            padding: 5px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button[name="register"]:hover {
            background-color: #5a5a6e;
            border-color: #343a40;
        }

        h3 {
            color: white; /* Change title text to white */
            font-weight: bold; /* Make it bold */
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7); /* Add shadow for better visibility */
        }
        /* Password input styling */
.input-group {
        position: relative;
    }

    /* Icon container */
    .password-toggle {
        background-color: #f8f9fa;
        cursor: pointer;
        transition: 0.3s ease-in-out;
        border-radius: 0px;
        padding: 8px;
    }

    /* Smooth icon transition */
    .password-toggle i {
        transition: 0.3s ease-in-out;
        color: #495057;
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
                    <center><b>Faculty Registration</b></center>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="email">Email ID</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter registered email">
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
                        <br>
                        <center>
                            <button type="submit"class="btn btn-warning"  name="register" id="register_button" style="background-color:rgb(0, 123, 255);" >Submit</button>
                        </center>  
                        <br>
                        <center><span>Already registered? <a href="index1.php">Login here</a></span></center>
                    </form>
                </div>
            </div><br>
            <span class="text-danger" id="msg"></span>
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

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
<script type="text/javascript" src="../includes/jquery_latest.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $("#email").blur(function(){
      var email = $(this).val();
      if(email == ""){
        $("#text").fadeOut();
      }
      else{
        $.ajax({
        url: "check-email.php",
        type: "POST",
        data: {email:email},
        success: function(data){
            $("#text").fadeIn().html(data);
            if(data == "<b>Email doesn't exist.</b>"){
                $("#register_button").prop("disabled",true);
                $("#msg").html("<b> * Please get registered yourself by admin first * </b>");
            }
            else{
                $("#register_button").prop("disabled",false);
                $("#msg").html("");
            }
        }
      });
      }
    });
  });
</script>

</html>
