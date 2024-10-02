<!DOCTYPE html>
<html lang="en">
<head>
    <!-- [Same as before] -->
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
        if (isset($_GET['page'])) {
            $page = $_GET['page'];

            // Apply directory traversal checks BEFORE decoding
            if (strpos($page, '../') !== false || strpos($page, '..\\') !== false) {
                die("Access denied.");
            }

            // Check for null byte injection
            if (strpos($page, chr(0)) !== false) {
                die("Null byte detected.");
            }

            // Only allow alphanumeric characters and underscores
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $page)) {
                die("Invalid page name.");
            }

            // Double URL decode the 'page' parameter AFTER the security checks
            $page = urldecode(urldecode($page));

            // Construct the file path without appending '.php'
            $filepath = "pages/" . $page;

            // Include the file if it exists
            if (file_exists($filepath)) {
                include($filepath);
            } else {
                echo "<h1>Page not found!</h1>";
                echo "<p>The page you're looking for does not exist.</p>";
            }
        } else {
            include("pages/home.php");  // Default page
        }
        ?>
    </div>

    <footer>
        <p>&copy; 2024 Vulnerable LFI Demo Site</p>
    </footer>

</body>
</html>
