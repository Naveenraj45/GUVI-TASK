<?php
require 'E:\xampp\htdocs\Task\vendor\autoload.php';
//using redis for session 
use Predis\Client;

$redis = new Client();

$servername = "localhost";
$username = "root";
$password = "";
$database = "guvitask";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loginUsername = isset($_POST['username']) ? $_POST['username'] : null;
$loginPassword = isset($_POST['password']) ? $_POST['password'] : null;

if ($loginUsername && $loginPassword) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $loginUsername, $loginPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        $response = array("status" => "error", "message" => $conn->error);
    } elseif ($result->num_rows > 0) {
        $sessionId = session_id();
        $redis->set($sessionId, $loginUsername);
        $response = array("status" => "success", "username" => $loginUsername);
    } else {
        $response = array("status" => "invalid");
    }

    $stmt->close();
} else {
    $response = array("status" => "invalid");
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
