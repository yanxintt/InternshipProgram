<?php 
include 'config.php'; 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4; 
            margin: 0; 
            padding: 0; 
        }

        .form-container {
            max-width: 400px; 
            margin: 50px auto; 
            padding: 20px; 
            background-color: white; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
        }

        form {
            display: flex; 
            flex-direction: column; 
        }

        label {
            margin-bottom: 5px; 
        }

        input[type="text"],
        input[type="password"],
        input[type="file"],
        input[type="date"],
        input[type="submit"] {
            padding: 10px; 
            margin-bottom: 15px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            font-size: 16px; 
        }

        input[type="submit"] {
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            cursor: pointer; 
            transition: background-color 0.3s; 
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            text-align: center; 
        }

        a {
            color: #4CAF50; 
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline; 
        }

        .back-to-login {
            background-color: #f4f4f4; 
            color: #4CAF50; 
            border: 1px solid #4CAF50; 
            padding: 10px 20px; 
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px; 
            text-align: center; 
            width: 100%; 
        }

        .back-to-login:hover {
            background-color: #e8f5e9;
            color: #388e3c; 
        }

    </style>
</head>
<body>
    <header class='header'>
        <a href="home.php" class="logo">Ametal <span>Tech Sdn. Bhd</span></a>
        <h1>Internship Job Assigned</h1>
    </header>

    <?php include 'navigation.php'; ?> 

    <div class="form-container">
        <h2>Create Account</h2>
        
        <?php

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $employeeID = $_POST['employeeID'];
            $position = $_POST['position'];
            $department = $_POST['department'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
            $birthday = $_POST['birthday'];
            $dateOfJoining = $_POST['dateOfJoining'];
            
            $avatarPath = 'uploads/default_avatar.png';  // Default image path

            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $avatarTmpName = $_FILES['avatar']['tmp_name'];
                $avatarName = basename($_FILES['avatar']['name']);
                $avatarExt = strtolower(pathinfo($avatarName, PATHINFO_EXTENSION));
        
                // Allow only specific file types
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
                if (in_array($avatarExt, $allowedExtensions)) {
                    // Generate a unique filename for the avatar image
                    $avatarNewName = uniqid() . '_' . $avatarName;
                    $avatarUploadDir = 'uploads/';  // Folder to store uploaded avatars
                    $avatarUploadPath = $avatarUploadDir . $avatarNewName;
        
                    // Move the uploaded file to the target directory
                    if (move_uploaded_file($avatarTmpName, $avatarUploadPath)) {
                        $avatarPath = $avatarUploadPath;  // Update avatar path
                    } else {
                        echo "Error uploading avatar.";
                    }
                } else {
                    echo "Invalid file type for avatar. Only JPG, JPEG, PNG, and GIF are allowed.";
                }
            }

            $sql = "INSERT INTO users (username, employee_id, position, department, password, birthday, date_of_joining, avatar) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $username, $employeeID, $position, $department, $password, $birthday, $dateOfJoining, $avatarPath);

            if ($stmt->execute()) {
                // After user creation, insert into employees table
                $insertEmployeeSQL = "INSERT INTO employees (id, position, department) VALUES (?, ?, ?)";
                $employeeStmt = $conn->prepare($insertEmployeeSQL);
                $employeeStmt->bind_param("iss", $employeeID, $position, $department);
                
                if ($employeeStmt->execute()) {
                    // Redirect to login or profile page after successful registration
                    header("Location: login.php");
                    exit();
                } else {
                    echo "Error adding employee to the list: " . $employeeStmt->error;
                }
                $employeeStmt->close();
            } else {
                echo "Error creating account: " . $stmt->error;
            }
            $stmt->close();
        }
        ?>


        <form action="signup.php" method="POST" enctype="multipart/form-data">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="employeeID">Employee ID:</label>
            <input type="text" id="employeeID" name="employeeID" required>

            <label for="position">Position:</label>
            <input type="text" id="position" name="position" required>

            <label for="department">Department:</label>
            <input type="text" id="department" name="department" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="birthday">Birthday:</label>
            <input type="date" id="birthday" name="birthday">

            <label for="dateOfJoining">Date of Joining:</label>
            <input type="date" id="dateOfJoining" name="dateOfJoining" required>

            <label for="avatar">Upload Avatar:</label>
            <input type="file" id="avatar" name="avatar" accept="image/*">

            <input type="submit" value="Create Account">
        </form>

    </div>

    <?php include 'footer.php'; ?> 

</body>
</html>
