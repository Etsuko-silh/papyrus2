<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
            <meta name="author" content="Deeshka">
            <meta name="description" content="project">
            <!--Viewport settings-->
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Papyrus</title>
            <!--font-->
            <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;500;700&display=swap" rel="stylesheet">
            
            <link rel="stylesheet" href="css/styles.css">

    </head>
    <body>
        <aside class="sidebar">
            <div class="aside_header">
            <img src="css/images/logo.png" alt="Logo" class="logo-icon">
            <h2>Papyrus</h2>

            </div>
            <div class="search-box">
                <img src="css/images/search.png" alt="Search" class="search-icon">
                <input type="text" id="search" placeholder="Search" autocomplete="off">
                <div id="search-popup">
                    <ul id="results"></ul>
                </div>
            </div>
            <div id="selected-book"></div>
            <button class="home">
                <img src="css/images/home.png" alt="Home" class="home-icon">
                <span class="home_text">HOME</span>
            </button>

            <script>
                document.querySelector('.home').addEventListener('click', function() {
                    window.location.href = 'student_page.php';
                });
            </script>
            <button class="notifications">
                <img src="css/images/notifications.png" alt="Notifications" class="notifications-icon">
                <span class="notifications_text">Notifications</span>
            </button>
            
            <div class="fixed-box">
                <button class="about">
                    <img src="css/images/about.png" alt="About" class="about-icon">
                    <span class="about_text">About</span>     
                </button>

                <script>
                    document.querySelector('.about').addEventListener('click', function() {
                        window.location.href = 'about_us.html';
                    });
                </script>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=nuradi.baiseitov26@fizmat.kz" target="_blank" class="help">
                    <img src="css/images/help.png" alt="Help" class="help-icon">
                    <span class="help_text">Help</span>     
                </a>

                <button class="app_version">
                    <img src="css/images/app_version.png" alt="App_version" class="app_version-icon">
                    <span class="app_version_text">App version</span>     
                </button>
            </div>
        </aside>

        <header class="header">
            <div class="header_inner">
                <h3>All books</h3>
                
            </div>
            <div class="navigation_bar">
                <button class="nav_item active" data-category="all">NEW</button>
                <button class="nav_item" data-category="physic">PHYSIC</button>  
                <button class="nav_item" data-category="math">MATH</button>
                <button class="nav_item" data-category="informatics">INFORMATICS</button>  
                <button class="nav_item" data-category="english">ENGLISH</button>   
                <button class="nav_item" data-category="olimpiads">OLIMPIADS</button>   
                <div class="nav_indicator"></div>
            </div>
            
        </header>
        
        <div class="content">
            <?php
            $conn = new mysqli("localhost", "root", "", "books_db");

            if ($conn->connect_error) {
                die("Ошибка подключения: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM books";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $category = !empty($row["category"]) ? strtolower($row["category"]) : "unknown";

                    echo "<div class='book-card' data-category='" . $category . "'>";
                    echo "<div class='book'>";
                    echo "<img src='" . $row["cover_image"] . "' alt='" . $row["title"] . "'>";
                    echo "<h3>" . $row["title"] . "</h3>";
                    echo "<p>" . $row["description"] . "</p>";
                    echo "<a href='" . $row["pdf_file"] . "' download>Скачать PDF</a>";
                    echo "</div>"; // Закрываем .book
            
                    echo "</div>"; // Закрываем .book-card
                }
            } else {
                echo "Нет доступных книг.";
            }
            

            $conn->close();
            ?>
        </div>
        <script src="filter.js"></script>
        <script src="navigation_bar.js"></script>
        <script src="script.js"></script>
        <script src="search.js"></script>
    </body>
</html>