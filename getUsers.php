<?php
include 'session.php';
// Database connection

$host = "localhost";

$dbname = "bugme";

$username = "root";

$password = "";



try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("Database connection failed: " . $e->getMessage());

}



try {

    $stmt = $pdo->query("SELECT id, firstname, lastname FROM users");

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);



    foreach ($users as $user) {

        echo "<option value='{$user['id']}'>{$user['firstname']} {$user['lastname']}</option>";

    }

} catch (PDOException $e) {

    die("Error fetching users: " . $e->getMessage());

}

?>
