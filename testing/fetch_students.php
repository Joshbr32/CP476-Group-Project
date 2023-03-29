<?php
$servername = "localhost";
$username = "cp476";
$password = "4v8b3gVScQpwm4CV";
$dbname = "cp476";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query
$sql = "SELECT student_id, student_name FROM `Name Table`";
$result = $conn->query($sql);

$data = array();

// Fetch the data from the result set
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }
} else {
  echo "0 results";
}

// Encode the data as JSON
echo json_encode($data);

// Close the connection
$conn->close();
?>
