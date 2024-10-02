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
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get the raw 'page' parameter from the query string
$query = $_SERVER['QUERY_STRING'];
preg_match('/(?:^|&)' . preg_quote('page', '/') . '=([^&]*)/', $query, $matches);
$page_raw = isset($matches[1]) ? $matches[1] : '';

// Single URL decode
$page_single_decode = urldecode($page_raw);

// Check for directory traversal (../ or ..\\)
if (strpos($page_single_decode, '../') !== false || strpos($page_single_decode, '..\\') !== false) {
    die("Access denied.");
}

// Check for null byte injection
if (strpos($page_single_decode, chr(0)) !== false) {
    die("Null byte detected.");
}

// Double URL decode to simulate bypass
$page = urldecode(urldecode($page_raw));

// Allow files in the "pages" directory (with .php extension)
$filepath = "pages/" . $page . ".php";

// Debugging output
echo "<div style='background-color: #f9f9f9; border: 1px solid #ccc; padding: 10px;'>";
echo "<strong>Debug Info:</strong><br>";
echo "page_raw: " . htmlspecialchars($page_raw) . "<br>";
echo "page_single_decode: " . htmlspecialchars($page_single_decode) . "<br>";
echo "page after double decode: " . htmlspecialchars($page) . "<br>";
echo "filepath: " . htmlspecialchars($filepath) . "<br>";
echo "</div>";

// If the page exists in "pages" directory, include it
if (file_exists($filepath)) {
    include($filepath);
} else {
    // Allow directory traversal if not found in pages/ directory (LFI)
    if (file_exists($page)) {
        // Include non-PHP files like config.ini
        $ext = pathinfo($page, PATHINFO_EXTENSION);
        if ($ext === 'php') {
            include($page);
        } else {
            // Output the contents safely
            echo "<pre>";
            echo htmlspecialchars(file_get_contents($page));
            echo "</pre>";
        }
    } else {
        echo "<h1>Page not found!</h1>";
        echo "<p>The page you're looking for does not exist.</p>";
    }
}
?>



    </div>

    <footer>
        <p>&copy; 2024 Lastmile Security Professionals</p>
    </footer>

</body>
</html>
