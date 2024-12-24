<?php
session_start();
include 'config.php';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $sql = "SELECT avatar FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $user = $userResult->fetch_assoc();
    $avatar = $user['avatar']; 
} else {
    $avatar = null; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Program</title>
    <link rel="stylesheet" href="style.css"> 

    <style>
        .logo {
            font-size: 24px;
            text-decoration: none;
            color: black;
        }

        .login {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            font-size: 18px;
            padding-right: 20px; 
        }

        .login img {
            margin-right: 8px; 
            width: 24px; 
            height: 24px;
        }

        #profileIcon img {
            border-radius: 50%;
            width: 30px;
            height: 30px;
        }
    </style>
</head>
<body>
    <header class='header'>
        <a href="home" class="logo">Ametal <span>Tech Sdn. Bhd</span></a>
        <h1>Internship Program</h1>       

        <?php if ($avatar): ?>
            <a href="selfprofile.php" id="profileIcon">
                <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Profile Icon" />
            </a>

        <?php else: ?>
            <a href="selfprofile.php" id="profileIcon">
                <<img width="50" height="50" src="https://img.icons8.com/?size=100&id=2Y92lnFK0NzQ&format=png&color=FFFFFF" alt="Profile Icon"/>
            </a>
        <?php endif; ?>
    </header>

    <?php include 'navigation.php'; ?>
    <?php include 'footer.php'; ?>
</body>
</html>
