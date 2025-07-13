<?php
session_start();
include('../includes/dbconn.php'); // Database connection

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$meals = ['Breakfast', 'Lunch', 'Dinner'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .wrapper {
            display: flex;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
            width: calc(100% - 260px);
        }
        .card {
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .modal-content {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="sidebar">
        <?php include('sidebar.php'); ?>
    </div>

    <div class="main-content">
        <div class="container mt-4">
            <h3 class="text-center"><b>Add New Food Schedule</b></h3>
            <hr>

            <div class="text-center">
                <button class="btn btn-success" data-toggle="modal" data-target="#addModal">Add New Meal</button>
                <a href="food_schedule.php" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</div>

<!-- Add New Meal Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Meal</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    <div class="form-group">
                        <label>Select Day</label>
                        <select id="day" name="day" class="form-control" required>
                            <option value="">-- Select Day --</option>
                            <?php foreach ($days as $day): ?>
                                <option value="<?php echo $day; ?>"><?php echo $day; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Meal Type</label>
                        <select id="mealType" name="meal_type" class="form-control" required>
                            <option value="">-- Select Meal --</option>
                            <?php foreach ($meals as $meal): ?>
                                <option value="<?php echo $meal; ?>"><?php echo $meal; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Menu</label>
                        <input type="text" id="menu" name="menu" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Save Meal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    $("#addForm").submit(function (event) {
        event.preventDefault();
        
        $.ajax({
            type: "POST",
            url: "save_new_schedule.php",
            data: $("#addForm").serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#addModal").modal("hide");
                    $("#addForm")[0].reset();
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function () {
                alert("Something went wrong..");
            }
        });
    });
});
</script>

</body>
</html>
