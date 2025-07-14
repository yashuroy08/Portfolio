<?php
require_once 'db.php';

if (isset($_GET['file']) && is_string($_GET['file'])) {
    $filename = basename($_GET['file']);
    $filepath = "books/" . $filename;

    // Check if file exists
    if (file_exists($filepath)) {
        // Update download count in database
        $stmt = $conn->prepare("UPDATE books SET download_count = download_count + 1 WHERE filename = ?");
        $stmt->execute([$filename]);

        // Send headers for download
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "Invalid file.";
}
