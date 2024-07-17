<!-- This is farmer_login.php-->
<?php
session_start(); // Start the session

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
$password = $_POST['password'];

// Check if the username exists in the 'users' table
$sql = "SELECT * FROM farmer WHERE farmer_id = '$farmer_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Username exists, check password
    $row = $result->fetch_assoc();
    if ($row['password'] == $password) {
        // Login successful, set session variable and redirect to booking page
        $_SESSION['farmer_id'] = $farmer_id;
        header("Location: booking.php");
        exit();
    } else {
        // Incorrect password, display error message
        echo "<div style='position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;'>
                <p style='color: red; font-size: 1.2em;'>Incorrect password. Please try again.</p>
                <button onclick=\"window.location.href='login.html'\">Login Again</button>
              </div>";
    }
} else {
    // Username does not exist, display error message and options
    echo "<div style='position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;'>
            <p style='color: red; font-size: 1.2em;'>Username doesn't exist.</p>
            <p>Would you like to:</p>
            <button onclick=\"window.location.href='login.html'\">Login Again</button> &nbsp;&nbsp;
            <button onclick=\"window.location.href='farmer_registration.html'\">Register</button>
          </div>";
}

// Close the database connection
$conn->close();
exit();
?>
