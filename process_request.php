<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $conn->real_escape_string(trim($_POST['id']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $admin = $conn->real_escape_string(trim($_POST['admin']));
    $type = $conn->real_escape_string(trim($_POST['type']));
    $subject = $conn->real_escape_string(trim($_POST['subject']));
    $details = $conn->real_escape_string(trim($_POST['details']));

    if (empty($employee_id) || empty($email) || empty($admin) || empty($type) || empty($subject) || empty($details)) {
        header("Location: request.php?error=missing_fields");
        exit();
    }

    $sql = "INSERT INTO requests (employee_id, email, admin, type, subject, details) 
            VALUES (?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $employee_id, $email, $admin, $type, $subject, $details);

    if ($stmt->execute()) {
        header("Location: request.php?status=success");
        exit();
    } else {
        header("Location: request.php?error=db_error");
        exit();
    }

    $stmt->close();
} else {
    header("Location: request.php");
    exit();
}

$conn->close();
?> 