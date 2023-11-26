<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "guvitask";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $conn->real_escape_string($_POST['username']);
$password = $conn->real_escape_string($_POST['password']);

// Checking whether username already exists in MySQL or not 
$checkUserQuery = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($checkUserQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    $response = array("status" => "error", "message" => $conn->error);
} elseif ($result->num_rows > 0) {
    $response = array("status" => "exists");
} else {
    // Inserting input data into MySQL using prepared statement
    $insertQuery = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ss", $username, $password);
    if ($stmt->execute()) {

        // MongoDB connection
        require_once '../vendor/autoload.php';
        $databaseConnection = new MongoDB\Client;
        $myDatabase = $databaseConnection->guvi1; // my database name
        $userCollection = $myDatabase->data1; // my collection name

        // MongoDB input fields
        $userData = [
            'username' => $username,
            'firstname' => "First Name",
            'lastname' => "Last Name",
            'age' => "00",
            'dateOfBirth' => '00-00-0000',
            'contactNumber' => '+91'
        ];

        // Inserting into MongoDB
        $userCollection->insertOne($userData);

        $response = array("status" => "success", "message" => 'Registered successfully');
    } else {
        $response = array("status" => "error", "message" => $stmt->error);
    }
    $stmt->close();
}

// Closing MySQL connection
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
