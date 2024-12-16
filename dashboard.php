<?php
include 'session.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bugme";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: {$conn->connect_error}");
}

$filter = $_GET['filter'] ?? 'ALL';
$sql = "SELECT issues.*, users.firstname, users.lastname FROM issues 
    LEFT JOIN users ON issues.assigned_to = users.id";
if ($filter !== 'ALL') {
    if (strpos($filter, 'MY_TICKETS') !== false) {
        $parts = explode('&', $filter);
        $assignedTo = isset($parts[1]) ? explode('=', $parts[1])[1] : '';
        if (!empty($assignedTo)) {
            $sql .= " WHERE issues.assigned_to = '{$assignedTo}'";
        }
    } else {
        $sql .= " WHERE issues.status = '{$filter}'";
    }
}
$result = $conn->query($sql);

function getStatusClass($status) {
    switch ($status) {
        case 'OPEN':
            return 'open';
        case 'CLOSED':
            return 'closed';
        case 'IN PROGRESS':
            return 'inProgress';
        default:
            return '';
    }
}

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $statusClass = getStatusClass($row["status"]);
        echo "<tr>";
        echo "<td># " . $row["id"] . " - " . "<a href='issue.html' id='descrip'>". $row["title"] . "</a>" . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td>" . $row["type"] . "</td>";
        echo "<td>" . $row["priority"] . "</td>";
        echo "<td id='status' class='{$statusClass}'>" . $row["status"] . "</td>";
        echo "<td>" . $row["firstname"] . " " . $row["lastname"] . "</td>";
        echo "<td>" . $row["created_at"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No results found</td></tr>";
}

$conn->close();