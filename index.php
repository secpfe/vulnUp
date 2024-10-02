<?php
// Vulnerable to Local File Inclusion
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // Restrict inclusion to the "pages" directory, but does not prevent traversal attacks
    if (strpos($page, '..') === false) {
        $filepath = "pages/" . $page . ".php";

        // Only include if the file exists in the pages directory
        if (file_exists($filepath)) {
            include($filepath);
        } else {
            echo "Page not found!";
        }
    } else {
        echo "Invalid page.";
    }
} else {
    echo "Welcome to the homepage! Use the 'page' parameter to navigate.";
}
?>
