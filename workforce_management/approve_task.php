<!-- this is approve_task.php -->
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

    // Approve the task by moving it from task_confirm to task table
    $approve_sql = "INSERT INTO task SELECT * FROM task_confirm WHERE task_id = '$task_id'";
    if ($conn->query($approve_sql) === TRUE) {
        // Delete the task from task_confirm table after approval
        $delete_sql = "DELETE FROM task_confirm WHERE task_id = '$task_id'";
        if ($conn->query($delete_sql) === TRUE) {
            // Display the details of the approved task including farm and farmer details
            $approved_task_sql = "SELECT t.*, f.farm_name, f.farm_location, f.farm_crop, fr.farmer_name, fr.address as farmer_address FROM task t
                                  INNER JOIN farm f ON t.farm_id = f.farm_id
                                  INNER JOIN farmer fr ON f.farmer_id = fr.farmer_id
                                  WHERE t.task_id = '$task_id'";
            $approved_task_result = $conn->query($approved_task_sql);
            if ($approved_task_result->num_rows > 0) {
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
                echo "<h2 class='success-message'>Approval Success</h2>";
                echo "<div class='table-container'>";
                echo "<table class='task-table'>";
                while ($row = $approved_task_result->fetch_assoc()) {
                    echo "<tr><td>Task ID:</td><td>" . $row["task_id"] . "</td></tr>";
                    echo "<tr><td>Task Name:</td><td>" . $row["task_name"] . "</td></tr>";
                    echo "<tr><td>Farm Name:</td><td>" . $row["farm_name"] . "</td></tr>";
                    echo "<tr><td>Farm Location:</td><td>" . $row["farm_location"] . "</td></tr>";
                    echo "<tr><td>Farm Crop:</td><td>" . $row["farm_crop"] . "</td></tr>";
                    echo "<tr><td>Farmer Name:</td><td>" . $row["farmer_name"] . "</td></tr>";
                    echo "<tr><td>Farmer Address:</td><td>" . $row["farmer_address"] . "</td></tr>";
                    echo "<tr><td>Start Date:</td><td>" . $row["start_date"] . "</td></tr>";
                    echo "<tr><td>Due Date:</td><td>" . $row["due_date"] . "</td></tr>";
                    // Display other task details as needed
                }
                echo "</table>";
                echo "<a href='w_view_task.php' class='view-task-button'>View Tasks</a>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "Error: Approved task details not found.";
            }
        } else {
            echo "Error deleting task from task_confirm: " . $conn->error;
        }
    } else {
        echo "Error approving task: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
