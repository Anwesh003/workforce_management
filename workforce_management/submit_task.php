<!-- This is submit_task.php-->
<?php
// Database connection parameters

// Check if the form is submitted for booking a task
if (isset($_POST['book_task'])) {
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

    // Extract form data
    $task_id = $_POST['task_id'];
    $task_name = $_POST['task_name'];
    $farm_id = $_POST['farm_id'];
    $start_date = $_POST['start_date'];
    $due_date = $_POST['due_date'];
    $worker_id = $_POST['worker_id'];

    // Insert data into the task_confirm table
    $sql = "INSERT INTO task_confirm (task_id, task_name, farm_id, worker_id, start_date, due_date) 
            VALUES ('$task_id', '$task_name', '$farm_id', '$worker_id', '$start_date', '$due_date')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to a confirmation page or display a success message
        header("Location: confirmation_page.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
