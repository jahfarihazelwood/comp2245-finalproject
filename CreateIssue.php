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

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $type = trim($_POST['type']);
        $priority = trim($_POST['priority']);
        $assigned_to = trim($_POST['assigned_to']);
        $creator_id = $_SESSION['user_id'];
    
        // Validation: Ensure all fields are provided
        if (empty($title) || empty($description) || empty($type) || empty($priority) || empty($assigned_to)) {
            die("Error: All fields are required.");
        }
    
        // Insert issue into the database
        $stmt = $conn->prepare("
            INSERT INTO issues (title, description, type, priority, status, assigned_to, created_by, created_at, updated_at)
            VALUES (?, ?, ?, ?, 'Open', ?, ?, NOW(), NOW())
        ");
        $stmt->bind_param("ssssii", $title, $description, $type, $priority, $assigned_to, $creator_id);
    
        if ($stmt->execute()) {
            header("Location: dashboard.html");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    
    }

    $stmt->close();

$conn->close();