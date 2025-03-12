<?php
require "config.php"; // Подключаем базу данных

$category = $_GET['category'] ?? 'new';

$sql = "SELECT * FROM books WHERE category = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);
?>
