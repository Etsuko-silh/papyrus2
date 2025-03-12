<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Обработка текстовых полей
    $title = htmlspecialchars(trim($_POST["title"] ?? ''));
    $description = htmlspecialchars(trim($_POST["description"] ?? ''));
    $category = htmlspecialchars(trim($_POST["category"] ?? ''));

    // Проверка обязательных полей
    if (empty($title) || empty($description) || empty($category)) {
        die("❌ Заполните все обязательные поля!");
    }

    // Папка для загрузки
    $upload_dir = __DIR__ . "/uploads/"; // Абсолютный путь

    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0755, true)) {
            die("❌ Ошибка создания папки uploads!");
        }
    }

    // Проверка наличия файлов
    if (empty($_FILES["cover"]["tmp_name"]) || empty($_FILES["pdf"]["tmp_name"])) {
        die("❌ Оба файла обязательны для загрузки");
    }

    // Проверка MIME-типов
    $allowed_image_types = ["image/jpeg", "image/png"];
    $allowed_pdf_type = "application/pdf";

    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    // Check if cover file exists before getting its MIME type
    if (file_exists($_FILES["cover"]["tmp_name"])) {
        $cover_mime = finfo_file($finfo, $_FILES["cover"]["tmp_name"]);
    } else {
        die("❌ Ошибка: файл обложки не найден.");
    }

    // Check if PDF file exists before getting its MIME type
    if (file_exists($_FILES["pdf"]["tmp_name"])) {
        $pdf_mime = finfo_file($finfo, $_FILES["pdf"]["tmp_name"]);
    } else {
        die("❌ Ошибка: PDF файл не найден.");
    }

    finfo_close($finfo);

    if (!in_array($cover_mime, $allowed_image_types)) {
        die("❌ Обложка должна быть JPG или PNG");
    }

    if ($pdf_mime !== $allowed_pdf_type) {
        die("❌ Файл должен быть PDF");
    }

    // Генерация имён файлов
    $cover_ext = pathinfo($_FILES["cover"]["name"], PATHINFO_EXTENSION);
    $cover_filename = uniqid('cover_', true) . ".$cover_ext";
    $pdf_filename = uniqid('pdf_', true) . ".pdf";

    // Перемещение файлов
    if (!move_uploaded_file($_FILES["cover"]["tmp_name"], $upload_dir . $cover_filename)) {
        die("❌ Ошибка загрузки обложки.");
    }

    if (!move_uploaded_file($_FILES["pdf"]["tmp_name"], $upload_dir . $pdf_filename)) {
        unlink($upload_dir . $cover_filename); // Удаляем уже загруженную обложку
        die("❌ Ошибка загрузки PDF.");
    }

    // Подключение к БД
    $conn = new mysqli("localhost", "root", "", "books_db");
    $conn->set_charset("utf8mb4");

    if ($conn->connect_error) {
        unlink($upload_dir . $cover_filename);
        unlink($upload_dir . $pdf_filename);
        die("❌ Ошибка подключения к БД: " . $conn->connect_error);
    }

    // Сохранение данных
    $stmt = $conn->prepare("INSERT INTO books (title, description, category, cover_image, pdf_file) VALUES (?, ?, ?, ?, ?)");
    $cover_path = "uploads/" . $cover_filename; // Относительный путь для БД
    $pdf_path = "uploads/" . $pdf_filename;
    $stmt->bind_param("sssss", $title, $description, $category, $cover_path, $pdf_path);

    if (!$stmt->execute()) {
        unlink($upload_dir . $cover_filename);
        unlink($upload_dir . $pdf_filename);
        die("❌ Ошибка записи в БД: " . $stmt->error);
    }

    echo "✅ Книга успешно загружена!";
    $stmt->close();
    $conn->close();
    ?>
    <br>
    <button onclick="window.location.href='teacher_page.php'">Перейти на главную</button>
    <button onclick="window.location.href='usersbook.html'">Загрузить еще</button>
    <?php
}
?>

<!-- Форма остается без изменений -->