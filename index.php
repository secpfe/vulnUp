<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerable LFI Demo Site</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        nav {
            background-color: #333;
            padding: 1em;
        }
        nav a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .content {
            margin: 20px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        footer {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    <nav>
        <a href="index.php?page=home">Home</a>
        <a href="index.php?page=about">About Us</a>
        <a href="index.php?page=services">Services</a>
        <a href="index.php?page=contact">Contact Us</a>
    </nav>

    <div class="content">
        <?php
        // Check if the page parameter is set
        if (isset($_GET['page'])) {
            $page = $_GET['page'];

            // Check for directory traversal (../ or ..\\)
            if (strpos($page, '../') !== false || strpos($page, '..\\') !== false) {
                die("Access denied.");
            }

            // Check for null byte injection
            if (strpos($page, chr(0)) !== false) {
                die("Null byte detected.");
            }

            // Double URL decode to simulate bypass
            $page = urldecode(urldecode($page));

            // Allow files in the "pages" directory (with .php extension)
            $filepath = "pages/" . $page . ".php";

            // If the page exists in "pages" directory, include it
            if (file_exists($filepath)) {
                include($filepath);
            } else {
                // Allow directory traversal if not found in pages/ directory (LFI)
                if (file_exists($page)) {
                    include($page); // Include non-PHP files like config.ini
                } else {
                    echo "<h1>Page not found!</h1>";
                    echo "<p>The page you're looking for does not exist.</p>";
                }
            }
        } else {
            include("pages/home.php");  // Default page
        }
        ?>
    </div>

    <footer>
        <p>&copy; 2024 Lastmile Security Professionals</p>
    </footer>

</body>
</html>
