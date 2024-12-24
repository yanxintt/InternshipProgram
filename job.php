<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Assigned Jobs</title>
    <link rel="stylesheet" href="style.css">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even){
            background-color: #f9f9f9;
        }

        tr:hover{
            background-color: #f1f1f1;
        }
        #filter-form {
         display: none;
        margin: 2px auto;
        width: 80%;
        max-width: 800px;
        display: flex;
        flex-direction: row; 
        justify-content: space-between; 
        gap: 10px;
        flex-wrap: wrap; 
    }

    #filter-form label {
        font-weight: bold;
        margin-right: 2px;
    }

    #filter-form input,
    #filter-form select {
        padding: 8px;
        font-size: 15px;
        border-radius: 5px;
        border: 1px solid #ccc;
        width: 100px; 
        position: relative; 
        top: -10px; 
    }

    #filter-form button {
        padding: 10px;
        font-size: 1rem;
        background-color: rgb(187, 214, 243);
        color: black;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
    }

    #filter-form button:hover {
        background-color: rgb(139, 181, 226);
    }

    .no-jobs-message {
        text-align: center;
        font-size: 1.2rem;
        color:rgb(148, 179, 212);
        margin-top: 20px;
    }
    </style>
</head>
<body>
    <header class="header">
        <a href="home.php" class="logo">Ametal <span>Tech Sdn. Bhd</span></a>
        <h1>My Assigned Jobs</h1>
    </header>
    
    <?php include 'navigation.php'; ?>

    <?php
        session_start();
        include 'config.php';?>

        <div id="search-container">
            <input type="text" id="search" placeholder="Search..." onkeyup="searchJobs()">
            <i class="fas fa-filter" onclick="toggleFilterForm()" aria-label="Filter"></i>
        </div>

    <?php include 'search.php'; ?>

    <form id="filter-form">
        <label for="due_date_from">From:</label>
        <input type="date" id="due_date_from">

        <label for="due_date_to">To:</label>
        <input type="date" id="due_date_to">

        <label for="priority">Priority:</label>
        <select id="priority">
            <option value="">All</option>
            <option value="Urgent">Urgent</option>
            <option value="Important">Important</option>
            <option value="Medium">Medium</option>
        </select>

        <label for="status">Status:</label>
        <select id="status">
            <option value="">All</option>
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
        </select>

        <button type="button" onclick="filterJobs()">Filter</button>
        <button type="button" onclick="clearFilter()">Clean Filter</button>
    </form>
    <main>
        <?php

        if (!isset($_SESSION['employee_id'])) {
            echo "<p>Please log in first.</p>";
            exit;
        }

        
        $loggedInEmployeeId = $_SESSION['employee_id'];

        $stmt = $conn->prepare("SELECT job_title, description, due_date, priority, status FROM job WHERE employee_id = ?");
        $stmt->bind_param("s", $loggedInEmployeeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<div id='jobs-table-container'>
                    <table id='jobs-table'>
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Description</th>
                                <th>Due Date</th>
                                <th>Priority</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['job_title']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['due_date']}</td>
                        <td>{$row['priority']}</td>
                        <td>{$row['status']}</td>
                      </tr>";
            }

            echo "</tbody></table></div>";
        } else {
            echo "<p class='no-jobs-message'>No jobs assigned to you at the moment.</p>";
        }
        ?>

    </main>

    <script>
        // Function to toggle the visibility of the filter form
        function toggleFilterForm() {
            const filterForm = document.getElementById('filter-form');
            filterForm.style.display = filterForm.style.display === 'none' || filterForm.style.display === '' ? 'flex' : 'none';
        }

        // Function to filter the jobs based on selected filters
        function filterJobs() {
            const dueDateFrom = document.getElementById('due_date_from').value;
            const dueDateTo = document.getElementById('due_date_to').value;
            const priority = document.getElementById('priority').value;
            const status = document.getElementById('status').value;

            const rows = document.querySelectorAll('#jobs-table tbody tr');
            let filteredRows = 0;

            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                const dueDate = cells[2].textContent.trim();
                const jobPriority = cells[3].textContent.trim();
                const jobStatus = cells[4].textContent.trim();

                let match = true;

                if (dueDateFrom && dueDate < dueDateFrom) match = false;
                if (dueDateTo && dueDate > dueDateTo) match = false;
                if (priority && jobPriority !== priority) match = false;
                if (status && jobStatus !== status) match = false;

                row.style.display = match ? '' : 'none';
                if (match) filteredRows++;
            });

            if (filteredRows === 0) {
                document.getElementById('jobs-table').innerHTML = "<p class='no-jobs-message'>No jobs found based on the selected filters.</p>";
            }
        }

        // Function to clear the filters
        function clearFilter() {
            document.getElementById('filter-form').reset();
            filterJobs(); // Reapply filter after reset
        }

        function searchJobs() {
            const input = document.getElementById('search').value.toLowerCase();
            const rows = document.querySelectorAll('#jobs-table tbody tr'); 
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

    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
