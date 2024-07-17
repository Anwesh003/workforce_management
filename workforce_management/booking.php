<!-- This is booking.php -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Details</title>
<style>
    /* CSS for centering the content */
    .centered-content {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        text-align: center;
    }

    /* CSS for table styling */
    .farm-table {
        width: 100%;
        border-collapse: collapse;
    }
    .farm-table th,
    .farm-table td {
        border: 1px solid #ddd;
        padding: 4px; /* Reduced padding for smaller row space */
        text-align: center; /* Center-align both table headings and values */
    }
    .farm-table th {
        background-color: #f2f2f2;
    }
    .farm-table th.action,
    .farm-table td.action {
        width: 100px; /* Width of the action column */
    }
    .add-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin: 5px;
    }
</style>
</head>
<body>
<div class="centered-content">
<?php
session_start(); // Start the session

// Check if farmer_id session variable is set
if (isset($_SESSION['farmer_id'])) {
    $farmer_id = $_SESSION['farmer_id'];

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

    // Retrieve data from the farm table for the logged-in farmer only
    $sql = "SELECT * FROM farm WHERE farmer_id = '$farmer_id'";
    $result = $conn->query($sql);

    echo "<h2>Your Farm Details:</h2>";

    if ($result->num_rows > 0) {
        // Output data in table format with CSS classes for styling
        echo "<table class='farm-table'>";
        echo "<tr><th>Farm ID</th><th>Farm Name</th><th>Farm Location</th><th>Farm Crop</th><th>Farmer ID</th><th class='action'>Action</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["farm_id"] . "</td>";
            echo "<td>" . $row["farm_name"] . "</td>";
            echo "<td>" . $row["farm_location"] . "</td>";
            echo "<td>" . $row["farm_crop"] . "</td>";
            echo "<td>" . $row["farmer_id"] . "</td>";
            // Button to book a worker for each row
            echo "<td class='action'><a href='book_worker.php?farm_id=" . $row["farm_id"] . "' class='add-button'>Book Worker</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "No farm data available for this farmer. <br>";
    }

    // Button to add another farm detail
    echo "<a href='add_farm.php' class='add-button'>Add Farm Detail</a>";
    // Button to go to f_view_task.php
echo "<a href='f_view_task.php' class='add-button'>View Tasks</a>";


    // Close the database connection
    $conn->close();
} else {
    // Redirect to login page if farmer_id session variable is not set
    header("Location: farmer_login.html");
    exit();
}
?>
</div>
</body>
</html>
