<?php
// Vulnerable to LFI
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // Basic path traversal protection (weak), can be bypassed by ../
    if (strpos($page, '..') === false) {
        // Include the requested page
        include($page . ".php");
    } else {
        echo "Invalid page.";
    }
} else {
    echo "Welcome to the homepage! Use the 'page' parameter to navigate.";
}
?>
