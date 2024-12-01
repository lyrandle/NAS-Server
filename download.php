<?php
$filePath = '/mnt/nas/' . $_GET['file'];

if (file_exists($filePath)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
    exit;
} else {
    echo "<script>alert('Error: File not found.'); window.location.href='dashboard.php';</script>";
}
?>

