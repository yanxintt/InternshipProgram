<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
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
            padding: 30px; 
            background-color: white; 
            border-radius: 10px; 
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
        </style>
</head>
<body>
    <header class='header'>
    <a href="home" class="logo">Ametal <span>Tech Sdn. Bhd</span></a>
        <h1>Internship Job Assigned</h1>
    </header>
    <?php include 'navigation.php'; ?>

    <div class="form-container">
        <h2>Login</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['login'])) {
                $employeeID = $_POST['employeeID'];
                $password = $_POST['password'];

                $sql = "SELECT * FROM users WHERE employee_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $employeeID);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['employee_id'] = $user['employee_id']; 
                        header("Location: selfprofile.php"); 
                        exit();
                    } else {
                        echo "Invalid password.";
                    }
                } else {
                    echo "No user found.";
                }
            }
        }
        ?>

        <form action="login.php" method="POST">
            <label for="employeeID">Employee ID:</label>
            <input type="text" id="employeeID" name="employeeID" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" name="login" value="Login">
        </form>

        <p>Don't have an account? <a href="signup.php">Create one here</a>.</p>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
