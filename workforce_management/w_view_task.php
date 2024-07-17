<!-- This is w_view_task.php-->
<?php
session_start(); // Start the session

if (!isset($_SESSION['worker_id'])) {
    // Redirect to login page if worker is not logged in
    header("Location: login.html");
    exit();
}

$worker_id = $_SESSION['worker_id'];

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "workforce_management"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching pending tasks to be confirmed by the worker
$sql_pending = "SELECT * FROM task_confirm WHERE worker_id = '$worker_id'";
$result_pending = $conn->query($sql_pending);

// Fetching upcoming tasks
$sql_upcoming = "SELECT * FROM task WHERE worker_id = '$worker_id' AND due_date > CURDATE()";
$result_upcoming = $conn->query($sql_upcoming);

// Fetching past tasks
$sql_past = "SELECT * FROM task WHERE worker_id = '$worker_id' AND due_date <= CURDATE()";
$result_past = $conn->query($sql_past);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tasks</title>
    <style>
        /* CSS styles */
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Button styles */
        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container a {
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .button-container a:hover {
            background-color: #0056b3;
        }

        @media only screen and (max-width: 600px) {
            /* Responsive adjustments */
            .container {
                padding: 10px;
            }

            table {
                font-size: 14px;
            }

            .button-container a {
                font-size: 14px;
            }
        }
    </style>    
</head>
<body>

<div class="container">
<h1><center>Notification Panel</center></h1>
<h2>Pending Tasks to Approve</h2>

<table>
    <tr>
        <th>Task ID</th>
        <th>Task Name</th>
        <th>Farm ID</th>
        <th>Start Date</th>
        <th>Due Date</th>
        <th>Action</th>
    </tr>
    <?php
    if ($result_pending->num_rows > 0) {
        while($row = $result_pending->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row["task_id"]."</td>";
            echo "<td>".$row["task_name"]."</td>";
            echo "<td>".$row["farm_id"]."</td>";
            echo "<td>".$row["start_date"]."</td>";
            echo "<td>".$row["due_date"]."</td>";
            echo "<td>";
            echo "<form action='approve_task.php' method='post' style='display: inline;'>";
            echo "<input type='hidden' name='task_id' value='".$row["task_id"]."'>";
            echo "<input type='hidden' name='task_name' value='".$row["task_name"]."'>";
            echo "<input type='hidden' name='farm_id' value='".$row["farm_id"]."'>";
            echo "<input type='hidden' name='start_date' value='".$row["start_date"]."'>";
            echo "<input type='hidden' name='due_date' value='".$row["due_date"]."'>";
            echo "<input type='hidden' name='worker_id' value='".$row["worker_id"]."'>";
            echo "<input type='submit' name='approve_task' value='Approve'>";
            echo "</form>";
            echo "<form action='reject_task.php' method='post' style='display: inline; margin-left: 5px;'>";
            echo "<input type='hidden' name='task_id' value='".$row["task_id"]."'>";
            echo "<input type='hidden' name='task_name' value='".$row["task_name"]."'>";
            echo "<input type='hidden' name='farm_id' value='".$row["farm_id"]."'>";
            echo "<input type='hidden' name='start_date' value='".$row["start_date"]."'>";
            echo "<input type='hidden' name='due_date' value='".$row["due_date"]."'>";
            echo "<input type='hidden' name='worker_id' value='".$row["worker_id"]."'>";
            echo "<input type='submit' name='reject_task' value='Reject'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No pending tasks found</td></tr>";
    }
    ?>
</table>


    <h2>Upcoming Tasks</h2>

    <table>
        <tr>
            <th>Task ID</th>
            <th>Task Name</th>
            <th>Farm ID</th>
            <th>Worker ID</th>
            <th>Start Date</th>
            <th>Due Date</th>
        </tr>
        <?php
        if ($result_upcoming->num_rows > 0) {
            while($row = $result_upcoming->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["task_id"]."</td>";
                echo "<td>".$row["task_name"]."</td>";
                echo "<td>".$row["farm_id"]."</td>";
                echo "<td>".$row["worker_id"]."</td>";
                echo "<td>".$row["start_date"]."</td>";
                echo "<td>".$row["due_date"]."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No upcoming tasks found</td></tr>";
        }
        ?>
    </table>

    <h2>Past Tasks</h2>

<table>
    <tr>
        <th>Task ID</th>
        <th>Task Name</th>
        <th>Farm ID</th>
        <th>Worker ID</th>
        <th>Start Date</th>
        <th>Due Date</th>
        <th>Feedback</th> <!-- New column for viewing feedback -->
    </tr>
    <?php
    if ($result_past->num_rows > 0) {
        while($row = $result_past->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row["task_id"]."</td>";
            echo "<td>".$row["task_name"]."</td>";
            echo "<td>".$row["farm_id"]."</td>";
            echo "<td>".$row["worker_id"]."</td>";
            echo "<td>".$row["start_date"]."</td>";
            echo "<td>".$row["due_date"]."</td>";
            
            echo "<td><a href='w_feedback_view.php?task_id=".$row["task_id"]."'>View Feedback</a></td>"; // Link to view feedback
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No past tasks found</td></tr>";
    }
    ?>
</table>


    <div class="button-container">
        <!-- Button to worker data -->
        <a href="bubble.php">Go to Worker data</a>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
