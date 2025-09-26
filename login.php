<?php
// Database connection details
$host = "your_host";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the request body
$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract data
    $username = $data['username'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT); // Hash the password

    // Prepare SQL statement
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sss", $username, $email, $password);

    // Execute the statement
    if ($stmt->execute()) {
        $response = array("status" => "success", "message" => "User created successfully");
    } else {
        $response = array("status" => "error", "message" => "Error creating user: " . $stmt->error);
    }

    // Close statement
    $stmt->close();
} else {
    $response = array("status" => "error", "message" => "Invalid request method");
}

// Close connection
$conn->close();

// Set response header
header('Content-Type: application/json');
echo json_encode($response);
?>
