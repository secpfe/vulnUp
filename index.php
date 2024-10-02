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
        // Vulnerable to Local File Inclusion
        if (isset($_GET['page'])) {
            $page = $_GET['page'];

            // Weak filtering for "../", but can be bypassed with URL encoding
            if (strpos($page, '../') === false && strpos($page, '..\\') === false) {
                $filepath = "pages/" . $page . ".php";

                // Only include if the file exists
                if (file_exists($filepath)) {
                    include($filepath);
                } else {
                    echo "<h1>Page not found!</h1>";
                    echo "<p>The page you're looking for does not exist.</p>";
                }
            } else {
                echo "<h1>Invalid page request.</h1>";
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
