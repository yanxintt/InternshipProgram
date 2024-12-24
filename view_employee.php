 <?php
include 'config.php'; 


if (isset($_GET['id'])) {
    $employeeID = $_GET['id'];

    $sql = "SELECT * FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employeeID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $employee = $result->fetch_assoc();
    } else {
        echo "Employee not found.";
        exit();
    }
} else {
    echo "No employee ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class='header'>
        <a href="home" class="logo">Ametal <span>Tech Sdn. Bhd</span></a>
        <h1>Employee Profile</h1>
    </header>

    <main>
        <div class="employee-details">
            <h2>Employee Details</h2>
            <p><strong>Employee ID:</strong> <?php echo $employee['id']; ?></p>
            <p><strong>Name:</strong> <?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?></p>
            <p><strong>Position:</strong> <?php echo $employee['position']; ?></p>
            <p><strong>Department:</strong> <?php echo $employee['department']; ?></p>
            <p><strong>Email:</strong> <?php echo $employee['email']; ?></p>
            <p><strong>Phone:</strong> <?php echo $employee['phone']; ?></p>
            <p><strong>Hire Date:</strong> <?php echo $employee['hire_date']; ?></p>
            <p><strong>Status:</strong> <?php echo $employee['status']; ?></p>
            <p><strong>Salary:</strong> <?php echo $employee['salary']; ?></p>
            <p><strong>Supervisor:</strong> <?php echo $employee['supervisor']; ?></p>
            <p><strong>Notes:</strong> <?php echo $employee['notes']; ?></p>
            <a href="profile.php">Back to Employee List</a>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html> 