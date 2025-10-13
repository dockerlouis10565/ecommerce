<?php
$path = isset($_GET['path'] )? urldecode($_GET['path']) : '';
$realPath = realpath($path);

// Security check: allow only specific folders
$allowedDir = realpath('C:/Users/Louis/Images/');
if (strpos($realPath, $allowedDir) === 0 && file_exists($realPath)) {
    header('Content-Type: image/jpeg'); // or detect MIME type dynamically
    readfile($realPath);
    exit;
}
http_response_code(403);
echo "Forbidden";
