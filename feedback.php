<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Feedback</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .requests-table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .requests-table th, 
        .requests-table td {
            padding: 12px 20px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .requests-table th {
            background-color:rgb(187, 214, 243);
            color: black;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
        }

        .requests-table tr:hover {
            background-color: #f5f5f5;
        }

        .status-pending {
            color: #dc3545;
        }

        .requests-table td.date-cell {
            white-space: nowrap;
        }

        .requests-table td.details {
            white-space: normal;
            word-wrap: break-word;
            max-width: 200px;
        }

    </style>
</head>
<body>
    <?php
    session_start();
    
    if (!isset($_SESSION['employee_id'])) {
        header("Location: login.php");
        exit();
    }

    $loggedInInternId = $_SESSION['employee_id'];
    echo "<!-- Debug: Logged in Intern ID: $loggedInInternId -->";
    ?>
    
    <header class='header'>
        <a href="home" class="logo">Ametal <span>Tech Sdn. Bhd</span></a>
        <h1>Supervisor Feedback</h1>
    </header>
    <?php include 'navigation.php'; ?>

    <div id="search-container">
        <input type="text" id="search" placeholder="Search..." onkeyup="search()">
    </div>

    <?php include 'search.php'; ?>

    <main>
        <section id="profile-details">
            <?php
            include 'config.php';

            if (!isset($_SESSION['employee_id'])) {
                echo "<p>Please log in first.</p>";
                exit;
            }

            $sql = "SELECT request_id, employee_id, email, admin, type, subject, details, feedback, 
                    DATE_FORMAT(created_at, '%d-%m-%Y %H:%i') as formatted_date 
                    FROM requests 
                    WHERE employee_id = ?
                    ORDER BY created_at DESC";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $loggedInInternId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table class='requests-table'>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Sent to</th>
                                <th>Type</th>
                                <th>Subject</th>
                                <th>Details</th>
                                <th>Feedback</th>
                            </tr>
                        </thead>
                        <tbody>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td class='date-cell'>{$row['formatted_date']}</td>
                            <td>{$row['admin']}</td>
                            <td>{$row['type']}</td>
                            <td>{$row['subject']}</td>
                            <td class='details'>{$row['details']}</td>
                            <td>" . 
                                ($row['feedback'] ? htmlspecialchars($row['feedback']) : 
                                "<span class='status-pending'>Pending</span>") . 
                            "</td>
                        </tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<p style='text-align: center; margin: 20px;'>No requests found.</p>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
