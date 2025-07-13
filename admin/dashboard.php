<?php 
session_start();
include('../includes/dbconn.php');

if(isset($_SESSION['email'])){
    // Fetch total boys count
    $query_boys = "SELECT COUNT(*) as boys_count FROM students WHERE gender = 'Male'";
    $result_boys = mysqli_query($connection, $query_boys);
    $row_boys = mysqli_fetch_assoc($result_boys);
    $boys_count = $row_boys['boys_count'];

    // Fetch total girls count
    $query_girls = "SELECT COUNT(*) as girls_count FROM students WHERE gender = 'Female'";
    $result_girls = mysqli_query($connection, $query_girls);
    $row_girls = mysqli_fetch_assoc($result_girls);
    $girls_count = $row_girls['girls_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="../includes/jquery_latest.js"></script>
    <title>HMS</title>
    <style>
        /* Base Layout */
.sidebar {
    width: 250px;
    background-color: #343a40;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    transition: transform 0.3s ease-in-out;
}

.main-content {
    margin-left: 250px;
    padding: 20px;
    transition: margin-left 0.3s;
}
.dashboard-card {
            background: #f8f9fa;
            border-radius: 15px;
            text-align: center;
            padding: 30px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
        }
        .dashboard-card:hover { transform: scale(1.05); }
        .boys-card { background:rgb(133, 138, 145); color: white; }
        .girls-card { background:rgb(133, 138, 145); color: white; }
</style>
</head>

<body style="background-color: #f8f9fa;">
<?php include('sidebar.php'); ?>
<br>
<div class="main-content">
    <nav class="">
        <h3>HOSTEL MANAGEMENT SYSTEM</h3>
    </nav>
<hr>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="dashboard-card boys-card" onclick="loadStudentList('Male')">
                    <h4>Total Boys</h4>
                    <h2><?php echo $boys_count; ?></h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-card girls-card" onclick="loadStudentList('Female')">
                    <h4>Total Girls</h4>
                    <h2><?php echo $girls_count; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div id="main_container"></div>
    </div>
</div>

<script>
 function toggleSidebar() {
        let sidebar = document.querySelector('.sidebar');
        let hmsTitle = document.getElementById('hms-title');
        
        sidebar.classList.toggle('closed');

        if (sidebar.classList.contains('closed')) {
            hmsTitle.style.display = 'none';
        } else {
            hmsTitle.style.display = 'block';
        }
    }

    function loadStudentList(gender) {
        $("#main_container").fadeOut(200, function(){
            $("#main_container").load("view_data.php?gender=" + gender, function(){
                $("#main_container").fadeIn(200);
            });
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
<?php 
} else {
  header('Location:../index.php');
}
?>
