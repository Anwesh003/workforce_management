<!-- This is reject_task.php-->
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

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are present
    if (!isset($_POST['task_id'])) {
        die("Error: Required field 'task_id' is missing.");
    }

    $task_id = $_POST['task_id'];

    // Check if the task exists and belongs to the logged-in worker
    $check_task_sql = "SELECT * FROM task_confirm WHERE task_id = '$task_id' AND worker_id = '$worker_id'";
    $result = $conn->query($check_task_sql);

    if ($result->num_rows == 0) {
        die("Error: Task ID '$task_id' does not exist or does not belong to you.");
    }

    // Retrieve task details before deleting from task_confirm table
    $task_details_sql = "SELECT * FROM task_confirm WHERE task_id = '$task_id'";
    $task_details_result = $conn->query($task_details_sql);

    if ($task_details_result->num_rows > 0) {
        $task_details_row = $task_details_result->fetch_assoc();
        $task_name = $task_details_row["task_name"];
        // Retrieve additional details about the task from related tables
        $farm_id = $task_details_row["farm_id"];
        $farm_sql = "SELECT * FROM farm WHERE farm_id = '$farm_id'";
        $farm_result = $conn->query($farm_sql);
        $farm_details = $farm_result->fetch_assoc();
        $farm_name = $farm_details["farm_name"];
        // Add more details as needed
    }

    // Delete the task from task_confirm table after rejection
    $delete_sql = "DELETE FROM task_confirm WHERE task_id = '$task_id'";
    if ($conn->query($delete_sql) === TRUE) {
        echo "
        <style>
            .container {
                text-align: center;
                margin-top: 50px;
            }

            .success-message {
                color: #4CAF50;
            }

            .table-container {
                width: 80%;
                margin: 0 auto;
                overflow-x: auto;
            }

            .task-table {
                width: 100%;
                border-collapse: collapse;
            }

            .task-table td, .task-table th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }

            .task-table th {
                background-color: #f2f2f2;
            }

            .view-task-button {
                background-color: #007bff;
                color: white;
                border: none;
                padding: 10px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin-top: 20px;
                cursor: pointer;
                border-radius: 5px;
            }

            .view-task-button:hover {
                background-color: #0056b3;
            }
        </style>
        ";
        echo "<div class='container'>";
        echo "<h2 class='success-message'>Task Rejection Success</h2>";
        echo "<div class='table-container'>";
        echo "<table class='task-table'>";
        echo "<tr><td>Task ID:</td><td>" . $task_id . "</td></tr>";
        echo "<tr><td>Task Name:</td><td>" . $task_name . "</td></tr>";
        echo "<tr><td>Farm Name:</td><td>" . $farm_name . "</td></tr>";
        // Add more task details here
        echo "</table>";
        echo "<a href='w_view_task.php' class='view-task-button'>View Tasks</a>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "Error deleting task from task_confirm: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
