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

// Double URL decode to simulate bypass
$page = urldecode(urldecode($page_raw));

// Single URL decode for security checks
$page_single_decode = urldecode($page_raw);

// Check for directory traversal in singly decoded input
if (strpos($page_single_decode, '../') !== false || strpos($page_single_decode, '..\\') !== false) {
    die("Access denied.");
}

// Check for null byte injection
if (strpos($page_single_decode, chr(0)) !== false) {
    die("Null byte detected.");
}

// Debugging output
echo "<div style='background-color: #f9f9f9; border: 1px solid #ccc; padding: 10px;'>";
echo "<strong>Debug Info:</strong><br>";
echo "page_raw: " . htmlspecialchars($page_raw) . "<br>";
echo "page_single_decode: " . htmlspecialchars($page_single_decode) . "<br>";
echo "page after double decode: " . htmlspecialchars($page) . "<br>";
echo "</div>";

// Base directory for pages
$base_dir = realpath(__DIR__ . '/pages/') . '/';

// Attempt to include from the 'pages' directory if no directory traversal is present
if (strpos($page, '../') === false && strpos($page, '..\\') === false) {
    // Construct the filepath
    $filepath = realpath($base_dir . $page . '.php');

    // Check if the file exists within the 'pages' directory
    if ($filepath && strpos($filepath, $base_dir) === 0 && file_exists($filepath)) {
        include($filepath);
        exit;
    }
}

// Attempt LFI if the file was not found in 'pages' directory
// Resolve the real path of the requested file
$page_path = realpath(__DIR__ . '/' . $page);

// Check if the file exists and is within the allowed directory
if ($page_path && strpos($page_path, __DIR__) === 0 && file_exists($page_path)) {
    // Include non-PHP files like config.ini
    $ext = pathinfo($page_path, PATHINFO_EXTENSION);
    if ($ext === 'php') {
        include($page_path);
    } else {
        // Output the contents safely
        echo "<pre>";
        echo htmlspecialchars(file_get_contents($page_path));
        echo "</pre>";
    }
} else {
    echo "<h1>Page not found!</h1>";
    echo "<p>The page you're looking for does not exist.</p>";
}
?>




    </div>

    <footer>
        <p>&copy; 404 Lastmile Security Professionals</p>
    </footer>

</body>
</html>
