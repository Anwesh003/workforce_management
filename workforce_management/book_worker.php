<!-- This is book_worker.php-->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Worker Details</title>
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
    .worker-table {
        width: 100%;
        border-collapse: collapse;
    }
    .worker-table th, .worker-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .worker-table th {
        background-color: #f2f2f2;
    }
    .book-button {
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 5px 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }

    /* New CSS class for the specific button */
    .farm-details-button {
        background-color: #007bff; /* Changed button color to blue */
    }
    
    .button-wrapper {
        margin-top: 20px; /* Adjust margin-top as needed */
    }
</style>
</head>
<body>
<div class="centered-content">
<?php
// Retrieve the farm ID from the URL parameter
if(isset($_GET['farm_id'])) {
    $farm_id = $_GET['farm_id'];
    echo "<h2>Booking for Farm ID: $farm_id</h2>";
} else {
    echo "<h2>No farm ID provided for booking</h2>";
}

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

// Retrieve data from the worker table
$sql = "SELECT * FROM worker";
$result = $conn->query($sql);

echo "<h2>Worker Details:</h2>";

if ($result->num_rows > 0) {
    // Output data in table format with CSS classes for styling
    echo "<table class='worker-table'>";
    echo "<tr><th>Worker ID</th><th>Worker Name</th><th>Phone Number</th><th>Skill</th><th>Salary</th><th>Available Workers</th><th>Action</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["worker_id"] . "</td>";
        echo "<td>" . $row["worker_name"] . "</td>";
        echo "<td>" . $row["ph_num"] . "</td>";
        echo "<td>" . $row["skill"] . "</td>";
        echo "<td>" . $row["salary"] . "</td>";
        echo "<td>" . $row["avail_no_workers"] . "</td>";
        echo "<td><button class='book-button' onclick='bookWorker(\"" . $row["worker_id"] . "\", \"" . $row["worker_name"] . "\", \"" . $row["ph_num"] . "\", \"" . $row["skill"] . "\")'>Book</button></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Add the button to go to booking.php
    echo "<div class='button-wrapper'><button class='book-button farm-details-button' onclick='window.location.href=\"booking.php\"'>Go back to FARM DETAILS</button></div>";
} else {
    echo "No worker data available. <br>";
}

// Close the database connection
$conn->close();
?>
<script>
    function bookWorker(workerId, workerName, phoneNumber, skill) {
        // Redirect to booking_process.php with both worker and farm IDs
        var urlParams = new URLSearchParams(window.location.search);
        var farmId = urlParams.get('farm_id');
        window.location.href = "booking_process.php?farm_id=" + farmId + "&worker_id=" + workerId + "&worker_name=" + workerName + "&ph_num=" + phoneNumber + "&skill=" + skill;
    }
</script>
</div>
</body>
</html>
