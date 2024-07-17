<!-- This is farm_data_registration.php-->
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

// Retrieve and sanitize form data
$farm_id = isset($_POST['farm_id']) ? $conn->real_escape_string($_POST['farm_id']) : '';
$farm_name = isset($_POST['farm_name']) ? $conn->real_escape_string($_POST['farm_name']) : '';
$farm_location = isset($_POST['farm_location']) ? $conn->real_escape_string($_POST['farm_location']) : '';
$farm_crop = isset($_POST['farm_crop']) ? $conn->real_escape_string($_POST['farm_crop']) : '';
$farmer_id = isset($_POST['farmer_id']) ? $conn->real_escape_string($_POST['farmer_id']) : '';

// Prepare the SQL statement to insert data into the farm table
$sql = "INSERT INTO farm (farm_id, farm_name, farm_location, farm_crop, farmer_id) 
        VALUES ('$farm_id', '$farm_name', '$farm_location', '$farm_crop', '$farmer_id')";

if ($conn->query($sql) === TRUE) {
    // Redirect to booking.php on successful insertion
    header("Location: booking.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
