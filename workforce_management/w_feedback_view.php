<!-- This is w_feedback_view.php-->
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

// Fetching task ID from the URL parameter
$task_id = $_GET['task_id'];

// Fetching task details along with feedback for the specific task
$sql_task_feedback = "SELECT t.task_name, t.farm_id, t.start_date, t.due_date, f.rating, f.comment 
                     FROM task t 
                     LEFT JOIN feedback f ON t.task_id = f.task_id 
                     WHERE t.task_id = '$task_id' AND f.worker_id = '$worker_id'";
$result_task_feedback = $conn->query($sql_task_feedback);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
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

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin-bottom: 10px;
        }

        li span {
            font-weight: bold;
        }

        .goback-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            cursor: pointer;
        }

        .goback-btn:hover {
            background-color: #45a049;
        }
    </style>    
</head>
<body>

<div class="container">
    <h2>Feedback for Task ID: <?php echo $task_id; ?></h2>

    <ul>
    <?php
if ($result_task_feedback->num_rows > 0) {
    while($row = $result_task_feedback->fetch_assoc()) {
        echo "<li><span>Task Name:</span> ".$row["task_name"]."</li>";
        echo "<li><span>Farm ID:</span> ".$row["farm_id"]."</li>";
        echo "<li><span>Start Date:</span> ".$row["start_date"]."</li>";
        echo "<li><span>Due Date:</span> ".$row["due_date"]."</li>";
        if(isset($row["rating"])) {
            // Check if rating exists
            echo "<li><span>Rating:</span> ".$row["rating"]."</li>";
        } else {
            // Display a message if rating doesn't exist
            echo "<li><span>Rating:</span> No rating available</li>";
        }
        echo "<li><span>Comment:</span> ".$row["comment"]."</li>";
    }
} else {
    echo "<li>No feedback found for this task</li>";
}
?>
    </ul>

    

</div>
<center><a href="javascript:history.back()" class="goback-btn">Go Back</a></center>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
