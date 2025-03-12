<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars(trim($_POST["title"]));
    $description = htmlspecialchars(trim($_POST["description"]));
    $category = htmlspecialchars(trim($_POST["category"]));

    $upload_dir = "uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Проверяем расширения файлов
    $cover_ext = strtolower(pathinfo($_FILES["cover"]["name"], PATHINFO_EXTENSION));
    $pdf_ext = strtolower(pathinfo($_FILES["pdf"]["name"], PATHINFO_EXTENSION));

    if (!in_array($cover_ext, ["jpg", "jpeg", "png"])) {
        die("Ошибка: Обложка должна быть в формате JPG или PNG.");
    }

    if ($pdf_ext !== "pdf") {
        die("Ошибка: Файл должен быть в формате PDF.");
    }

    // Генерируем уникальные имена файлов
    $cover_filename = uniqid() . "." . $cover_ext;
    $pdf_filename = uniqid() . ".pdf";

    $cover_path = $upload_dir . $cover_filename;
    $pdf_path = $upload_dir . $pdf_filename;

    // Перемещаем загруженные файлы
    if (move_uploaded_file($_FILES["cover"]["tmp_name"], $cover_path) &&
        move_uploaded_file($_FILES["pdf"]["tmp_name"], $pdf_path)) {

        $conn = new mysqli("localhost", "root", "", "books_db");
        $conn->set_charset("utf8mb4");

        if ($conn->connect_error) {
            die("Ошибка подключения: " . $conn->connect_error);
        }

        // SQL-запрос через `prepare`
        $stmt = $conn->prepare("INSERT INTO books (title, description, category, cover_image, pdf_file) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $description, $category, $cover_path, $pdf_path);

        if ($stmt->execute()) {
            echo "✅ Книга успешно загружена!";
        } else {
            echo "❌ Ошибка загрузки: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "❌ Ошибка загрузки файлов!";
    }
}
?>

<!-- Форма для загрузки -->
<form method="post" enctype="multipart/form-data">
    <label>Название книги:</label>  
    <input type="text" name="title" required><br><br>

    <label>Описание:</label>  
    <textarea name="description" required></textarea><br><br>

    <label>Категория:</label>  
    <select name="category">
        <option value="Физика">Физика</option>
        <option value="Математика">Математика</option>
        <option value="Информатика">Информатика</option>
        <option value="Английский">Английский</option>
        <option value="Олимпиады">Олимпиады</option>
    </select><br><br>

    <label>Обложка (JPG, PNG):</label>  
    <input type="file" name="cover" accept="image/jpeg, image/png" required><br><br>

    <label>PDF файл:</label>  
    <input type="file" name="pdf" accept="application/pdf" required><br><br>

    <button type="submit">Загрузить</button>
</form>
