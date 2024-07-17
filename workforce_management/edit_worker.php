<!-- This is edit_worker.php-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Worker</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Worker</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php
                session_start(); // Start the session

                if (!isset($_SESSION['worker_id'])) {
                    // Redirect to login page if worker is not logged in
                    header("Location: login.html");
                    exit();
                }

                $worker_id = $_GET['worker_id'];

                $servername = "localhost";
                $username = "root"; 
                $password = ""; 
                $dbname = "workforce_management"; 

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Process form submission
                    $skill = $_POST['skill'];
                    $salary = $_POST['salary'];
                    $avail_no_workers = $_POST['avail_no_workers'];

                    // Update worker information in the database
                    $sql = "UPDATE worker SET skill='$skill', salary='$salary', avail_no_workers='$avail_no_workers' WHERE worker_id='$worker_id'";
                    if ($conn->query($sql) === TRUE) {
                        echo '<div class="alert alert-success" role="alert">Record updated successfully</div>';
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Error updating record: ' . $conn->error . '</div>';
                    }
                }

                // Fetch worker's current information from the database
                $sql = "SELECT worker_id, worker_name, ph_num, skill, salary, avail_no_workers FROM worker WHERE worker_id = '$worker_id'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?worker_id=" . $worker_id); ?>">
                    <div class="form-group">
                        <label for="skill">Skill:</label>
                        <input type="text" class="form-control" id="skill" name="skill" value="<?php echo $row['skill']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="salary">Salary:</label>
                        <input type="text" class="form-control" id="salary" name="salary" value="<?php echo $row['salary']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="avail_no_workers">Available Number of Workers:</label>
                        <input type="text" class="form-control" id="avail_no_workers" name="avail_no_workers" value="<?php echo $row['avail_no_workers']; ?>">
                    </div>
                    <button type="submit" class="btn btn-success">Update</button>
                </form>
                <a href="bubble.php" class="btn btn-primary mt-3">Go Back</a>
            </div>
        </div>
    </div>
</body>
</html>
