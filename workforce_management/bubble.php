<!-- This is bubble.php -->
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

$sql = "SELECT worker_id, worker_name, ph_num, skill, salary, avail_no_workers FROM worker WHERE worker_id = '$worker_id'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f4f4f4;
        }
        .registration-button {
    display: block;
    width: 200px;
    margin: 20px auto;
    text-align: center;
    padding: 10px;
    background-color: #4CAF50; /* Green */
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.registration-button:hover {
    background-color: #45a049; /* Darker shade of green on hover */
}

    </style>
</head>
<body>
    <h1>Worker Data</h1>
    
    <table>
        <tr>
            <th>Worker ID</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Skill</th>
            <th>Salary</th>
            <th>Available Workers</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["worker_id"] . "</td>";
                echo "<td>" . $row["worker_name"] . "</td>";
                echo "<td>" . $row["ph_num"] . "</td>";
                echo "<td>" . $row["skill"] . "</td>";
                echo "<td>" . $row["salary"] . "</td>";
                echo "<td>" . $row["avail_no_workers"] . "</td>";
                echo "<td><a href='edit_worker.php?worker_id=" . $row["worker_id"] . "' class='edit-button'>Edit</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>0 results</td></tr>";
        }
        $conn->close();
        ?>
    </table>
    
    <a href="worker_registration.html" class="registration-button">ADD OTHER WORKERS</a>
    <a href="w_view_task.php" class="registration-button">View Notifications</a>
</body>
</html>
