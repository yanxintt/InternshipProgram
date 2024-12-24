<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>

        .form-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border-radius: 80px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto 20px;
        }

        .profile-info {
            margin: 20px 0;
            font-size: 18px;
            color: #555;
        }

        .profile-info strong {
            color: #333;
        }

        button {
            background-color: rgb(186,197,245);
            color: rgb(3,11,46);
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 20px;
        }

        button:hover {
            background-color: rgb(149,166,237);
        }

        .logout-btn {
            background-color: #f44336;
            color: white;
            border: 1px solid #d32f2f;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 20px;
            width: 100%;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #e57373;
        }

        .go-back-btn {
            background-color: rgb(186,197,245);
            color: rgb(3,11,46);
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }

        .go-back-btn:hover {
            background-color: rgb(149,166,237);
        }

        .add-employee-form {
            display: none;
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 6px;
            margin-top: 20px;
            border: 1px solid #ddd;
        }

        .add-employee-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .add-employee-form input[type="text"], .add-employee-form input[type="submit"] {
            width: 90%;
            padding: 10px;
            margin: 1px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }

        .add-employee-form input[type="submit"] {
            background-color: #1e88e5;
            color: white;
            font-weight: 500;
            cursor: pointer;
        }

        .add-employee-form input[type="submit"]:hover {
            background-color: #1565c0;
        }

        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.4); 
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
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
    <h2>Your Profile</h2>
    <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" class="avatar">
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Employee ID:</strong> <?php echo htmlspecialchars($user['employee_id']); ?></p>
    <p><strong>Position:</strong> <?php echo htmlspecialchars($user['position']); ?></p>
    <p><strong>Department:</strong> <?php echo htmlspecialchars($user['department']); ?></p>
    <p><strong>Birthday:</strong> <?php echo htmlspecialchars($user['birthday']); ?></p>
    <p><strong>Date of Joining:</strong> <?php echo htmlspecialchars($user['date_of_joining']); ?></p>
    
    <button id="toggleAddForm">Edit Profile</button>
    <div id="editProfileForm" class="add-employee-form" style="display: none;">
        <h3>Edit Your Profile</h3>
        <form action="update_profile.php" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="position">Position:</label>
            <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($user['position']); ?>" required>

            <label for="department">Department:</label>
            <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($user['department']); ?>" required>

            <input type="submit" value="Update Profile">
        </form>
    </div>

    <button onclick="window.location.href='login.php';" class="go-back-btn">Go Back to Login</button>

    <form action="logout.php" method="POST">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>


    <script>
        document.getElementById('toggleAddForm').addEventListener('click', function() {
            var form = document.getElementById('editProfileForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block'; 
            } else {
                form.style.display = 'none'; 
            }
        });
    </script>

    <?php include 'footer.php'; ?> 
</body>
</html>