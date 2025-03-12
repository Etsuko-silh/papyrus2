<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "books_db");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$query = isset($_GET["q"]) ? $conn->real_escape_string($_GET["q"]) : "";

$sql = "SELECT title 
        FROM books 
        WHERE title LIKE '%$query%'
        LIMIT 5";

$result = $conn->query($sql);
$books = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

echo json_encode($books, JSON_UNESCAPED_UNICODE);
$conn->close();
?>