
<?php
include_once 'config/Database.php';
include_once 'class/User.php';
include_once 'class/Post.php';
include_once 'class/Category.php';



$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$post = new Post($db);
$category = new Category($db);

if (isset($_POST["uploadPdf"])) {
    // Handle file upload
    $uploadedFile = $_FILES["pdf_file"]["tmp_name"];

// Execute the Python script
$python_script = "pdfextract.py";
$command = "python $python_script \"$uploadedFile\"";
$result = shell_exec($command);

// Output the result as JSON
header("Content-Type: application/json");
//echo $result;
    // Output the raw API response for debugging
  // Output the raw API response for debugging
  echo $result;

    // Decode the JSON result from Python
    $python_result = json_decode($result, true);
    $additionalMessage = ''; // Initialize the variable

    // Check for JSON decoding errors
    $json_error_code = json_last_error();
    if ($json_error_code !== JSON_ERROR_NONE) {
        if ($json_error_code === JSON_ERROR_SYNTAX) {
            $additionalMessage = "You can't use this feature offline. Please check your internet connection and try again. You have to input manually.";
        } else {
            $additionalMessage = "JSON decoding error (code $json_error_code): " . json_last_error_msg();
        }
        // echo "JSON decoding error (code $json_error_code): " . json_last_error_msg();
        // Handle the error as needed
    } else {
        // Check if data exists in the results
        if (isset($python_result["results"])) {
            // Populate PHP variables with Python results
            $_POST["title"] = $python_result["results"]["title"];
            $_POST["capstonemembers"] = isset($python_result["results"]["author"]) ? $python_result["results"]["author"] : "";
            $_POST["message"] = isset($python_result["results"]["introduction"]) ? $python_result["results"]["introduction"] : "";
        } else {
            // Handle case where no data is extracted
            $additionalMessage = "No data extracted from the PDF.";
        }
    }
}
?>