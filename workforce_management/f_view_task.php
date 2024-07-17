<!-- This is f_view_task.php -->
<?php
session_start(); // Start the session

if (!isset($_SESSION['farmer_id'])) {
    // Redirect to login page if farmer is not logged in
    header("Location: login.html");
    exit();
}

$farmer_id = $_SESSION['farmer_id'];

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "workforce_management"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching pending tasks for farmer approval
$sql_pending = "SELECT * FROM task_confirm WHERE farm_id IN (SELECT farm_id FROM farm WHERE farmer_id = '$farmer_id')";
$result_pending = $conn->query($sql_pending);

// Fetching upcoming tasks
$sql_upcoming = "SELECT * FROM task WHERE farm_id IN (SELECT farm_id FROM farm WHERE farmer_id = '$farmer_id') AND due_date > CURDATE()";
$result_upcoming = $conn->query($sql_upcoming);

// Fetching past tasks along with feedback option
$sql_past = "SELECT t.*, f.feedback_id FROM task t LEFT JOIN feedback f ON t.task_id = f.task_id WHERE t.farm_id IN (SELECT farm_id FROM farm WHERE farmer_id = '$farmer_id') AND t.due_date <= CURDATE()";
$result_past = $conn->query($sql_past);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tasks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .button-container {
            margin-top: 20px;
            text-align: center;
        }
        .button-container a {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .button-container a:hover {
            background-color: #0056b3;
        }
    </style>    
</head>
<body>

<div class="container">
    <h2>Pending Approval Tasks</h2>

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
        if ($result_pending->num_rows > 0) {
            while($row = $result_pending->fetch_assoc()) {
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
            echo "<tr><td colspan='6'>No pending approval tasks found</td></tr>";
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
            <th>Feedback</th>
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
                echo "<td>";
if ($row["feedback_id"]) {
    echo "<a href='view_feedback.php?feedback_id=".$row["feedback_id"]."' style='color: #00cc00;'>View Feedback</a>";
} else {
    echo "<a href='feedback_form.php?task_id=".$row["task_id"]."&farmer_id=".$farmer_id."&worker_id=".$row["worker_id"]."'>Give Feedback</a>";
}
echo "</td>"; 
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No past tasks found</td></tr>";
        }
        ?>
    </table>

    <div class="button-container">
        <!-- Button to go to farmer dashboard or other page -->
        <a href="booking.php">Go to Booking page</a>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
