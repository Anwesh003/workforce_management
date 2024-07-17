<!-- This is worker_data_registration.php-->
<?php
// Assuming you have a MySQL database running on XAMPP
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "workforce_management"; // Replace with your actual database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$worker_id = $_POST['worker_id'];
$worker_name = $_POST['worker_name'];
$ph_num = $_POST['ph_num'];
$password = $_POST['password'];
$skill = $_POST['skill'];
$salary=$_POST['salary'];
$avail_no_workers=$_POST['avail_no_workers'];

// Check if the worker_id already exists
$check_query = "SELECT * FROM worker WHERE worker_id='$worker_id'";
$check_result = $conn->query($check_query);

if ($check_result->num_rows > 0) {
    // Worker ID already exists, handle the error (e.g., display a message)
    echo "<div style='position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align:center;'>";
    echo "<p style='color: red;'>Error: Worker ID already exists. Please choose a different Worker ID.</p>";
    echo "<button style='background-color: #3498db; /* Blue */
    border: none;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 5px;' onclick='window.location.href=\"worker_registration.html\"'>Go back to registration page</button>";
    echo "</div>";
} else {
    // Worker ID does not exist, proceed with insertion
    // Insert data into the 'worker' table
    $sql = "INSERT INTO worker (worker_id, worker_name, ph_num, password, skill, salary, avail_no_workers) VALUES ('$worker_id', '$worker_name', '$ph_num', '$password', '$skill', '$salary', '$avail_no_workers')";

    if ($conn->query($sql) === TRUE) {
        // Registration successful, redirect to home page
        header("Location: login2.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
