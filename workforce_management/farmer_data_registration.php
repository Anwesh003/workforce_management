<!-- This is farmer_data_registration.php-->
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
$farmer_id = $_POST['farmer_id'];
$farmer_name = $_POST['farmer_name'];
$phone_no = $_POST['phone_no'];
$password = $_POST['password'];
$address = $_POST['address'];

// Insert data into the 'users' table
$sql = "INSERT INTO farmer (farmer_id, farmer_name, phone_no, password, address) VALUES ('$farmer_id', '$farmer_name', '$phone_no', '$password', '$address')";

if ($conn->query($sql) === TRUE) {
    // Registration successful, redirect to home page
    header("Location:login1.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>