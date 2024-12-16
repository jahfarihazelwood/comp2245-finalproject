<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bugme";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: {$conn->connect_error}");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_firstname'] = $user['firstname'];
            $_SESSION['user_lastname'] = $user['lastname'];
            // Redirect to Dashboard.html
            header("Location: Dashboard.html");
            exit();
        } else {
            // Return failure response
            echo json_encode(['success' => false, 'message' => 'Invalid password']);
        }
    } else {
        // Return failure response
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }

    $stmt->close();
}

$conn->close();