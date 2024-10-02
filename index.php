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

    // Apply security checks
    if (strpos($page, '../') !== false || strpos($page, '..\\') !== false) {
        die("Access denied.");
    }
    if (strpos($page, chr(0)) !== false) {
        die("Null byte detected.");
    }

    // Double URL decode
    $page = urldecode(urldecode($page));

    // Remove any remaining directory components
    $page = basename($page);

    // Attempt to include from 'pages' directory
    $filepath = "pages/" . $page . ".php";
    if (file_exists($filepath)) {
        include($filepath);
    } else {
        // Attempt to include directly from root directory
        if (file_exists($page)) {
            include($page);
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
        <p>&copy; 2024 LM Security</p>
    </footer>

</body>
</html>
