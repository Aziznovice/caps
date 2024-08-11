<?php

include_once 'config/Database.php';
include_once 'class/User.php';
include_once 'class/students.php';
include_once 'class/Post.php';
include_once 'class/Category.php';
include_once 'process_pdf.php';
 


$database = new Database();
$db = $database->getConnection();
$student = new student($db);
$user = new User($db);
$post = new Post($db);
$category = new Category($db);

if (!$user->loggedIn()) {
    header("location: index.php");
}

$categories = $post->getCategories();

$post->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : 0;

$saveMessage = '';

if (!empty($_POST["savePost"]) && $_POST["title"] != '' && $_POST["message"] != '') {

    $post->title = $_POST["title"];
    $post->message = $_POST["message"];
    $post->capstonemembers = $_POST["capstonemembers"];
    $post->capstone_advisor = $_POST["capstone_advisor"];
    $post->capstone_mentor = $_POST["capstone_mentor"];
    $post->panel_member = $_POST["panel_member"];
    $post->copyright = strtoupper($_POST["copyright"]);
    $post->pdfdisplay = isset($_POST["display"]) ? $_POST["display"] : 0;
    $post->category = $_POST["category"];
    $post->status = $_POST["status"];

    if ($post->id) {
        $post->updated = date('Y-m-d H:i:s');
        if ($post->update()) {
            $saveMessage = "Post updated successfully!"; 
        }
    } else {
        $post->userid = $_SESSION["userid"];
        $post->created = date('Y-m-d H:i:s');
        $post->updated = date('Y-m-d H:i:s');
        $lastInsertId = $post->insert();
        if ($lastInsertId) {
            $post->id = $lastInsertId; // Assign the last inserted ID to $post->id
            $saveMessage = "Post saved successfully!";
            if (!empty($_FILES['pdf_save']['name'])) {
                $pdfFileName = $post->id . '_' . $post->sanitizeTitle($_POST['title']) . '.pdf';
                $pdfFilePath = 'pdf/' . $pdfFileName;
            
                if (move_uploaded_file($_FILES['pdf_save']['tmp_name'], $pdfFilePath)) {
                    $post->pdf_name = $pdfFileName;
                    $post->updatePdfName();
                } else {
                    $additionalMessage = "Error uploading PDF file.";
                }
            }
        }
        if ($lastInsertId) {
            $post->id = $lastInsertId; // Assign the last inserted ID to $post->id
            $saveMessage = "Post saved successfully!";
        }


        if ($lastInsertId) {
            $post->id = $lastInsertId;
            $saveMessage = "Post saved successfully!";

            // Reset input values for a new post
            $_POST["title"] = '';
            $_POST["message"] = '';
            $_POST["capstonemembers"] = '';
            $_POST["capstone_advisor"] = '';
            $_POST["capstone_mentor"] = '';
            $_POST["panel_member"] = '';
            $_POST["category"] = '';
            $_POST["status"] = '';
            $_POST["copyright"] = '';
        }
    }
}

$postdetails = $post->getPost();
$additionalMessage = '';



include('inc/header.php');
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/posts.js"></script>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php include "menus.php"; ?>
    <?php include 'inc/header2.php'?>
    <br>
    <section id="main">
        <div class="container">
            <div class="row">
                <?php include "left_menus.php"; ?>
                <div class="col-md-9">
                <?php if ($saveMessage != '') { ?>
                        <div id="login-alert" class="alert alert-success col-sm-12">
                            <?php echo $saveMessage; ?>
                        </div>
                    <?php } ?>
                    <?php if ($additionalMessage != '') { ?>
                        <div id="additional-alert" class="alert alert-danger col-sm-12">
                            <?php echo $additionalMessage; ?>
                        </div>
                    <?php } ?>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add New Post</h3>
                        </div>
                        <div class="panel-body">
                            <!-- Include jQuery library -->

                            <!-- Your existing code -->
                            <!-- Your existing code -->
                            <form method="post" id="pdfForm" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="pdf_file" class="control-label">Upload a PDF File for Data Extraction:</label>
                                    <input type="file" name="pdf_file" id="pdf_file" onchange="updateFileName(this)">
                                </div>
                                <span id="selectedPdfName"></span>
                                <input type="submit" name="uploadPdf" id="uploadPdf" value="Upload PDF">
                                <div id="loadingSpinner" style="display: none;"><img src="css/Book.gif"
                                        alt="Loading..."></div>
                            </form>


                            <script>
                                function uploadPdf(e) {
                                    e.preventDefault(); // Prevent the default form submission

                                    // Show loading spinner
                                    $("#loadingSpinner").show();

                                    // Get the form element
                                    var form = e.target;

                                    // Create a FormData object
                                    var formData = new FormData(form);

                                    // Add the 'uploadPdf' parameter to the form data
                                    formData.append('uploadPdf', 1);

                                    // Use $.ajax for asynchronous form submission
                                    $.ajax({
                                        type: "POST",
                                        url: "process_pdf.php",
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        success: function (response) {
                                            if (response.results && response.results.title) {
                                                $("#title").val(response.results.title);
                                                $("#capstonemembers").val(response.results.author);
                                                $("#message").val(response.results.introduction);
                                            } else {
                                                // Handle the case where 'title' is not present in the response
                                                console.error("Title not found in response:", response);
                                            }
                                            $("#loadingSpinner").hide();
                                        },

                                        error: function (xhr, status, error) {
                                            // Hide loading spinner on error
                                            $("#loadingSpinner").hide();

                                            // Handle errors
                                            console.error(xhr.responseText);
                                        }
                                    });
                                }

                                // Attach the event handler to the form submission
                                $("#pdfForm").submit(uploadPdf);


                                $(document).ready(function () {
        // Initial check when the page is loaded
        updateFileName(document.getElementById("pdf_file"));
        
        // Attach the event handler to the file input change event
        $("#pdf_file").change(function () {
            updateFileName(this);
        });
    });

    function updateFileName(input) {
        const selectedPdfName = document.getElementById("selectedPdfName");
        const uploadPdfButton = document.getElementById("uploadPdf");

        if (input.files.length > 0) {
            selectedPdfName.textContent = "Selected PDF File: " + input.files[0].name;
            // Enable the "Upload PDF" button
            uploadPdfButton.disabled = false;
        } else {
            selectedPdfName.textContent = "";
            // Disable the "Upload PDF" button
            uploadPdfButton.disabled = true;
        }
    }
                            </script>
                            <form method="post" id="postForm" enctype="multipart/form-data">

                                <div class="form-group">
                                    <label for="title" class="control-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        placeholder="Post title.."
                                        value="<?php echo isset($_POST['title']) ? $_POST['title'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="lastname" class="control-label">Abstract</label>
                                    <textarea class="form-control" rows="5" id="message" name="message"
                                        placeholder="Write a Abstract"><?php echo isset($_POST['message']) ? $_POST['message'] : ''; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="capstonemembers" class="control-label">Capstone Members</label>
                                    <input type="text" class="form-control" id="capstonemembers" name="capstonemembers"
                                        placeholder="Please use semicolon ';' to separate multiple individuals when there are two or more people."
                                        value="<?php echo isset($_POST['capstonemembers']) ? $_POST['capstonemembers'] : ''; ?>">
                                </div>


                                <div class="form-group">
                                    <label for="capstone_advisor" class="control-label">Capstone Advisor</label>
                                    <input type="text" class="form-control" id="capstone_advisor"
                                        name="capstone_advisor" placeholder="Please input the name of the advisor...">
                                </div> 

                                <div class="form-group">
                                    <label for="capstone_mentor" class="control-label">Chairperson</label>
                                    <input type="text" class="form-control" id="capstone_mentor" name="capstone_mentor"
                                        placeholder="Please input the name of the Mentor...">
                                </div>

                                <div class="form-group">
                                    <label for="panel_member" class="control-label">Defense Panel Member</label>
                                    <input type="text" class="form-control" id="panel_member" name="panel_member"
                                        placeholder="Please use semicolon ';' to separate multiple individuals when there are two or more people.">
                                </div>




                                <div class=" form-group">
                                    <label for="sel1">Select School Year</label>
                                    <select class="form-control" id="category" name="category">
                                        <?php
                                        while ($category = $categories->fetch_assoc()) {
                                            $selected = '';
                                            if ($category['name'] == $postdetails['name']) {
                                                $selected = 'selected="selected"';
                                            }
                                            echo "<option value='" . $category['id'] . "' $selected>" . $category['name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="status" class="control-label"></label>
                                    <label class="radio-inline">
                                        <input type="radio" name="status" id="publish" value="published" <?php {
                                            echo "checked";
                                        } ?>>Publish
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="status" id="draft" value="draft" <?php {
                                            echo "checked";
                                        } ?>>Draft
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="status" id="archived" value="archived" <?php {
                                            echo "checked";
                                        } ?>>Archive
                                    </label>

                                    <br><br>
                                    <div class="form-group">
                                        <label for="copyright" class="control-label">Copyright No.</label>
                                        <input type="text" class="form-control" id="copyright" name="copyright"
                                            placeholder="CopyRights No.">
                                    </div>
                                    <div class="form-group">
    <label for="pdf_save" class="control-label">Select PDF File for Download:</label>
    <input type="file" name="pdf_save" id="pdf_save">
    <small class="form-text text-muted">Please upload a PDF file that users can download.</small>
</div>
<input type="hidden" name="pdfdisplay" id="pdfdisplay" value="0"> 
    <input type="checkbox" id="pdfdisplayCheckbox" name="display" value="1">
    <label for="pdfdisplayCheckbox"> Display the PDF</label><br>
                                </div>

                        </div>
                        <input type="submit" name="savePost" id="savePost" class="btn btn-info" value="Save" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <?php include('inc/footer.php'); ?>