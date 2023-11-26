<?php
require_once '../vendor/autoload.php'; // Composer autoload

use MongoDB\Client;

// Connecting with MongoDB
$databaseConnection = new Client;
$myDatabase = $databaseConnection->guvi1;
$userCollection = $myDatabase->data1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['username'])) {
    $username = $_POST['username'];
    error_log('Received username: ' . $username);

    // Check whether data fetch or update
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
      $updateResult = $userCollection->updateOne(
        ['username' => $username],
        ['$set' => [
          'firstname' => $_POST['firstname'],
          'lastname' => $_POST['lastname'],
          'age' => $_POST['age'],
          'dateOfBirth' => $_POST['dob'],
          'contactNumber' => $_POST['contact'],
        ]]
      );

      if ($updateResult->getModifiedCount() > 0) {
        if (isset($_POST['newPassword'])) {
          $newPassword = $_POST['newPassword'];
          updatePasswordInMySQL($username, $newPassword);
        }
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
      } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Failed to update user data']);
      }
    } else {
      // Finding username in MongoDB
      $userData = $userCollection->findOne(['username' => $username]);

      if ($userData) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $userData]);
      } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'User data not found']);
      }
    }
  } else {
    // Return error if username is not provided
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Username not provided']);
  }
}

// password updation in MySQL
function updatePasswordInMySQL($username, $newPassword) {
  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "guvitask";

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $newPassword = $conn->real_escape_string($newPassword);

  // Updating the password in MySQL
  $updatePasswordQuery = "UPDATE users SET password = '$newPassword' WHERE username = '$username'";
  $conn->query($updatePasswordQuery);

  // Closing MySQL connection
  $conn->close();
}
?>