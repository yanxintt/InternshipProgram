<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .add-employee-form {
            display: none; 
            margin-top: 20px; 
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
        }

        #toggleAddForm {
            display: inline-block; 
            padding: 10px 20px; 
            margin: 20px 200px; 
            border: none; 
            border-radius: 5px; 
            background-color: #a1cbf7; 
            color: black; 
            font-size: 16px; 
            cursor: pointer; 
            transition: background-color 0.3s; 
        }

        #toggleAddForm:hover {
            background-color: #a1cbf7; 
        }

        .button-container {
            display: flex; 
            justify-content: center; 
            margin-top: 20px; 
        }

        .icon {
            width: 15px; 
            height: 15px; 
        }

        #search {
            padding: 10px; 
            font-size: 1rem; 
            width: 100%; 
            max-width: 580px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            margin-bottom: 10px;
            transition: border-color 0.3s; 
        }

        #search:focus {
            border-color: #007BFF; 
            outline: none; 
        }

        .header-container {
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 8px; 
        }

        .header-container h2 {
            margin: 0; 
            font-size: 1.5rem; 
        }

        #exportButton {
            padding: 10px 20px; 
            background-color: #a1cbf7; 
            color: black; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: background-color 0.3s; 
        }

        #exportButton:hover {
            background-color: #89c4f4;
        }


    </style>
</head>
<body>
    <header class='header'>
        <a href="home" class="logo">Ametal <span>Tech Sdn. Bhd</span></a>
        <h1>Employee List</h1>
    </header>

    <?php include 'navigation.php'; ?>
    <?php include 'config.php'; ?>

    <main>
        <div class='a'>
            <div class="header-container">
                <h2>Employee List</h2>
                <button id="exportButton" onclick="exportToCSV()">Export to CSV</button>
            </div>

            <input type="text" id="search" placeholder="Search employees..." onkeyup="searchEmployees()">

            <table>
                <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $Message = "";

                    //Delete
                    if (isset($_GET['delete'])) {
                        $deleteID = $_GET['delete'];
                        // Use prepared statements to prevent SQL injection
                        $deleteSQL = "DELETE FROM employees WHERE id = ?";
                        $stmt = $conn->prepare($deleteSQL);
                        $stmt->bind_param("i", $deleteID);
                        if ($stmt->execute()) {
                            $Message = "alert('Employee deleted successfully');";
                        } else {
                            $Message = "alert('Error deleting employee: " . $conn->error . "');";
                        }
                        $stmt->close();
                    }

                    //Edit
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editEmployeeID'])) {
                        $editEmployeeID = $_POST['editEmployeeID'];
                        $editPosition = $_POST['editPosition'];
                        $editDepartment = $_POST['editDepartment'];

                        // Use prepared statements for update
                        $updateSQL = "UPDATE employees SET position = ?, department = ? WHERE id = ?";
                        $stmt = $conn->prepare($updateSQL);
                        $stmt->bind_param("ssi", $editPosition, $editDepartment, $editEmployeeID);
                        if ($stmt->execute()) {
                            $Message = "alert('Employee updated successfully');";
                        } else {
                            $Message = "alert('Error updating employee: " . $conn->error . "');";
                        }
                        $stmt->close();
                    }
                    
                    //Add Employee
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['editEmployeeID'])) {
                        $employeeID = $_POST['employeeID'];
                        $position = $_POST['position'];
                        $department = $_POST['department'];

                        // Check for duplicate employee ID
                        $checkSQL = "SELECT * FROM employees WHERE id = ?";
                        $stmt = $conn->prepare($checkSQL);
                        $stmt->bind_param("i", $employeeID);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            $Message = "alert('Employee ID already exists.');";
                        } else {
                            // Use prepared statements for insert
                            $insertSQL = "INSERT INTO employees (id, position, department) VALUES (?, ?, ?)";
                            $stmt = $conn->prepare($insertSQL);
                            $stmt->bind_param("iss", $employeeID, $position, $department);
                            if ($stmt->execute()) {
                                $Message = "alert('Employee added successfully');";
                            } else {
                                $Message = "alert('Error adding employee: " . $conn->error . "');";
                            }
                            $stmt->close();
                        }
                    }

                    // Display employee
                    $sql = "SELECT * FROM employees ORDER BY department ASC, position ASC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['position']}</td>
                                <td>{$row['department']}</td>
                                <td>
                                    <a href='?edit={$row['id']}'><img src='https://img.icons8.com/material-outlined/24/000000/edit--v1.png' class='icon' alt='Edit'/></a> | 
                                    <a href='?delete={$row['id']}'><img src='https://img.icons8.com/material-outlined/24/000000/delete--v1.png' class='icon' alt='Delete'/></a> 
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No employees found</td></tr>";
                    }
                ?>
                </tbody>
            </table>

            <?php
            //edit form
            if (isset($_GET['edit'])) {
                $editID = $_GET['edit'];
                $editSQL = "SELECT * FROM employees WHERE id = '$editID'";
                $editResult = $conn->query($editSQL);

                if ($editResult->num_rows == 1) {
                    $editRow = $editResult->fetch_assoc();
                    ?>
                    <h3>Edit Employee</h3>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                        <input type="hidden" name="editEmployeeID" value="<?php echo $editRow['id']; ?>">

                        <label for="editPosition">Position:</label>
                        <select id="editPosition" name="editPosition" required>
                            <option value="Intern" <?php if ($editRow['position'] == 'Intern') echo 'selected'; ?>>Intern</option>
                            <option value="Project Manager" <?php if ($editRow['position'] == 'Project Manager') echo 'selected'; ?>>Project Manager</option>
                            <option value="Marketing Specialist" <?php if ($editRow['position'] == 'Marketing Specialist') echo 'selected'; ?>>Marketing Specialist</option>
                            <option value="Business Analyst" <?php if ($editRow['position'] == 'Business Analyst') echo 'selected'; ?>>Business Analyst</option>
                            <option value="Human Resource Personnel" <?php if ($editRow['position'] == 'Human Resource Personnel') echo 'selected'; ?>>Human Resource Personnel</option>
                            <option value="Accountant" <?php if ($editRow['position'] == 'Accountant') echo 'selected'; ?>>Accountant</option>
                            <option value="Other" <?php if ($editRow['position'] == 'Other') echo 'selected'; ?>>Other</option>
                        </select><br>

                        <label for="editDepartment">Department:</label>
                        <select id="editDepartment" name="editDepartment" required>
                            <option value="IT" <?php if ($editRow['department'] == 'IT') echo 'selected'; ?>>IT</option>
                            <option value="HR" <?php if ($editRow['department'] == 'HR') echo 'selected'; ?>>HR</option>
                            <option value="Finance" <?php if ($editRow['department'] == 'Finance') echo 'selected'; ?>>Finance</option>
                            <option value="Sales" <?php if ($editRow['department'] == 'Sales') echo 'selected'; ?>>Sales</option>
                            <option value="Marketing" <?php if ($editRow['department'] == 'Marketing') echo 'selected'; ?>>Marketing</option>
                            <option value="Other" <?php if ($editRow['department'] == 'Other') echo 'selected'; ?>>Other</option>
                        </select><br>

                        <input type="submit" value="Update Employee">
                    </form>
                    <?php
                }
            }
            ?>

            <button id="toggleAddForm">Add Employee</button>
            <div id="addEmployeeForm" class="add-employee-form">
                <h3>Add Employee</h3>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <label for="employeeID">Employee ID:</label>
                    <input type="number" id="employeeID" name="employeeID" required>

                    <label for="position">Position:</label>
                    <select id="position" name="position" required>
                        <option value="" disabled selected>Select position</option>
                        <option value="Intern">Intern</option>
                        <option value="Project Manager">Project Manager</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Sales">Sales</option>
                        <option value="Human Resource Personnel">Human Resource Personnel</option>
                        <option value="Accountant">Accountant</option>
                        <option value="Other">Other</option>
                    </select>

                    <label for="department">Department:</label>
                    <select id="department" name="department" required>
                        <option value="" disabled selected>Select department</option>
                        <option value="IT">IT</option>
                        <option value="HR">HR</option>
                        <option value="Finance">Finance</option>
                        <option value="Sales">Sales</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Other">Other</option>
                    </select>

                    <input type="submit" value="Add Employee">
                </form>
            </div>

            <h3>Upload Employee Data from CSV</h3>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                <input type="file" name="employeeCSV" accept=".csv" required>
                <input type="submit" name="uploadCSV" value="Upload CSV">
            </form>

        </div>
    </main>

    <script>
        document.getElementById('toggleAddForm').addEventListener('click', function() {
            var form = document.getElementById('addEmployeeForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block'; 
            } else {
                form.style.display = 'none'; 
            }
        });

        function searchEmployees() {
            const input = document.getElementById('search').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                let found = false;
                for (let cell of cells) {
                    if (cell.textContent.toLowerCase().includes(input)) {
                        found = true;
                        break;
                    }
                }
                row.style.display = found ? '' : 'none';
            });
        }

        const deleteLinks = document.querySelectorAll('a[href*="delete"]');
        deleteLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                if (!confirm('Are you sure you want to delete this employee?')) {
                    event.preventDefault(); 
                }
            });
        });

        function exportToCSV() {
            const link = document.createElement('a');
            link.href = 'export.php'; 
            link.download = 'employees.csv'; 
            document.body.appendChild(link);
            link.click(); 
            document.body.removeChild(link); 
        }
    </script>

    <?php
    if (!empty($Message)) {
        echo "<script>{$Message}</script>";
    }
    ?>

<?php

if (isset($_POST['uploadCSV'])) {
    $file = $_FILES['employeeCSV']['tmp_name'];
    if (($handle = fopen($file, 'r')) !== FALSE) {
        fgetcsv($handle);
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {

            if (count($data) >= 3) {
                $employeeID = isset($data[0]) ? trim($data[0]) : null; 
                $position = isset($data[1]) ? trim($data[1]) : null; 
                $department = isset($data[2]) ? trim($data[2]) : null; 

                if ($employeeID !== null) { 
                    $checkSQL = "SELECT * FROM employees WHERE id = ?";
                    $stmt = $conn->prepare($checkSQL);
                    $stmt->bind_param("i", $employeeID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows == 0) {
                        $insertSQL = "INSERT INTO employees (id, position, department) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($insertSQL);
                        $stmt->bind_param("iss", $employeeID, $position, $department);
                        $stmt->execute();
                    }
                    $stmt->close();
                } else {
                    $Message = "alert('Employee ID is missing in the CSV row.');";
                }
            } else {
                $Message = "alert('Row does not contain enough data.');";
            }
        }
        fclose($handle);
        $Message = "alert('Employee data uploaded successfully');";
    } else {
        $Message = "alert('Error opening the CSV file.');";
    }
}

?>

<?php include 'footer.php'; ?>
</body>
</html>
