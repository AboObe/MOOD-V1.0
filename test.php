<?php
$servername = "mariadb-database-1.cdpgacbggcj0.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "]G6u}eG_GHE[.#}";

$dbname = "mood";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM categories";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "id: " . $row["id"]. " - Name: " . $row["name"]. " " . $row["image"]. "<br>";
  }
} else {
  echo "0 results";
}
$conn->close();
?>