<?php
include 'config.php'; 

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="employees.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Employee ID', 'Position', 'Department']);

$sql = "SELECT id, position, department FROM employees ORDER BY department ASC, position ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

fclose($output);
exit();
?> 