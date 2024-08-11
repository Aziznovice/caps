<!-- pdf_viewer.php -->
<?php
$file = $_GET['file'];

if (file_exists($file)) {
    // Set appropriate headers to display the PDF in the browser
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($file) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    header("Content-Security-Policy: default-src 'self';");
    echo "<div>Loading PDF...</div>";

    // Output the PDF content
    readfile($file);
} else {
    echo ">PDF is not available";
}
?>
