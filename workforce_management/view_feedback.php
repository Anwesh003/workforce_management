<!-- This is view_feedback.php-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .feedback-details {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .feedback-details p {
            margin: 5px 0;
        }
        .button-container {
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
    <h1>Feedback Details</h1>

    <?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "workforce_management"; // Replace with your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if feedback ID is provided in the URL
if(isset($_GET['feedback_id'])) {
    // Sanitize the input
    $feedback_id = $conn->real_escape_string($_GET['feedback_id']);

    // Fetch feedback data from the database
    $sql = "SELECT f.*, t.task_name, t.start_date AS task_start_date, t.due_date AS task_due_date, 
                   fa.farm_name, fa.farm_location, 
                   fw.worker_name AS worker_name, fw.ph_num AS worker_phone
            FROM feedback f
            INNER JOIN task t ON f.task_id = t.task_id
            INNER JOIN farm fa ON t.farm_id = fa.farm_id
            INNER JOIN worker fw ON f.worker_id = fw.worker_id
            WHERE f.feedback_id = '$feedback_id'";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of the feedback
        while($row = $result->fetch_assoc()) {
            echo "<div class='feedback-details'>";
            echo "<p><strong>Feedback ID:</strong> " . $row["feedback_id"]. "</p>";
            echo "<p><strong>Farmer ID:</strong> " . $row["farmer_id"]. "</p>";
            echo "<p><strong>Worker ID:</strong> " . $row["worker_id"]. "</p>";
            echo "<p><strong>Task ID:</strong> " . $row["task_id"]. "</p>";
            echo "<p><strong>Task Name:</strong> " . $row["task_name"]. "</p>";
            echo "<p><strong>Task Start Date:</strong> " . $row["task_start_date"]. "</p>";
            echo "<p><strong>Task Due Date:</strong> " . $row["task_due_date"]. "</p>";
            echo "<p><strong>Farm Name:</strong> " . $row["farm_name"]. "</p>";
            echo "<p><strong>Farm Location:</strong> " . $row["farm_location"]. "</p>";
            echo "<p><strong>Rating:</strong> " . $row["rating"] . "/5</p>"; // Display rating as number
            echo "<p><strong>Comment:</strong> " . $row["comment"]. "</p>";
            echo "<p><strong>Worker Name:</strong> " . $row["worker_name"]. "</p>";
            echo "<p><strong>Worker Phone:</strong> " . $row["worker_phone"]. "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No feedback found with the provided ID.</p>";
    }
} else {
    echo "<p>No feedback ID provided.</p>";
}

// Close the database connection
$conn->close();
?>


    <div class="button-container">
        <!-- Button to go back -->
        <a href="javascript:history.back()">Go Back</a>
    </div>
</div>

</body>
</html>
