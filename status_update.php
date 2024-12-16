<?php
include 'session.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bugme";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: {$conn->connect_error}");
}

$issueId = $_GET['id'] ?? '';

if (!empty($issueId)) {
    $sql = "SELECT issues.*, users.firstname, users.lastname FROM issues 
            LEFT JOIN users ON issues.assigned_to = users.id 
            WHERE issues.id = '{$issueId}'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $issue = $result->fetch_assoc();
        echo json_encode([
            'issueName' => $issue['title'],
            'issueID' => $issue['id'],
            'issueDescription' => $issue['description'],
            'created' => $issue['created'],
            'updatedOn' => $issue['updated'],
            'assignedTo' => $issue['firstname'] . ' ' . $issue['lastname']
        ]);
    } else {
        echo json_encode(['error' => 'Issue not found']);
    }
} else {
    echo json_encode(['error' => 'No issue ID provided']);
}

$conn->close();