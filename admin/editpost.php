<?php

include_once 'config/Database.php';
include_once 'class/User.php';
include_once 'class/students.php';
include_once 'class/Post.php';
include_once 'class/Category.php'; 

$database = new Database();
$db = $database->getConnection(); 
$student = new student($db);
$user = new User($db);
$post = new Post($db);
$category = new Category($db);

if(!$user->loggedIn()) {
	header("location: index.php");
}

$categories = $post->getCategories();

$post->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : 0;
$saveMessage = '';

if(!empty($_POST["savePost"]) && $_POST["title"] != '' && $_POST["message"] != '') {
	
	$post->title = $_POST["title"];
	$post->message = $_POST["message"];
	$post->capstonemembers= $_POST["capstonemembers"];
	$capstone_members_data = $_POST["capstonemembers"];
    if (!is_array($capstone_members_data)) {
        $capstone_members_data = explode(";", $capstone_members_data);
    }
    // Convert the array of names to a string with "-" as the separator
    $capstone_members_str = implode(";", $capstone_members_data);
	$post-> capstonemembers = $capstone_members_str;

	$post->capstone_advisor= $_POST["capstone_advisor"];
	$post->capstone_mentor= $_POST["capstone_mentor"]; 
	$panel_member_data = $_POST["panel_member"];
    if (!is_array($panel_member_data)) {
        $panel_member_data = explode(";", $panel_member_data);
    }
    // Convert the array of names to a string with "-" as the separator
    $panel_member_str = implode(";", $panel_member_data);
    $post->panel_member = $panel_member_str;
	$post->copyright = strtoupper($_POST["copyright"]);
	$post->category = $_POST["category"];
	// $post->pdf_name = isset($_POST["pdf_name"]) ? $_POST["pdf_name"] : '';
	$post->status = $_POST["status"]; 
	$post->pdfdisplay = isset($_POST["pdfdisplay"]) ? $_POST["pdfdisplay"] : 0;
	
	if($post->id) {	
		$post->updated = date('Y-m-d H:i:s');
		if($post->update()) {
			$saveMessage = "Post updated successfully!";
if ($post->update()) {
    // Check if a new PDF file is uploaded
    if (!empty($_FILES['pdf_file']['name'])) {
        // Delete the old PDF file associated with the post
        $postdetails = $post->getPost();
        if (!empty($postdetails['pdf_name'])) {
            $oldPdfFilePath = 'pdf/' . $postdetails['pdf_name'];
            if (file_exists($oldPdfFilePath)) {
                // Attempt to delete the old file
                if (unlink($oldPdfFilePath)) {
                    // File deletion successful
                } else {
                    // File deletion failed
                    $additionalMessage = "Error deleting old PDF file.";
                }
            }
        }

        // Upload the new PDF file
        $pdfFileName = $post->id . '_' . $post->sanitizeTitle($_POST['title']) . '.pdf';
        $pdfFilePath = 'pdf/' . $pdfFileName;

        if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $pdfFilePath)) {
            // Update the PDF name in the post
            $post->pdf_name = $pdfFileName;
            $post->updatePdfName();
        } else {
            $additionalMessage = "Error uploading PDF file.";
        }
    }

    $saveMessage = "Post updated successfully!";
    $_SESSION['editpost'] = $saveMessage;
    header("Location: posts.php");
    exit();
}

}

 

		
	} else {
		$post->userid = $_SESSION["userid"];
		$post->created = date('Y-m-d H:i:s'); 
		$post->updated = date('Y-m-d H:i:s'); 	
		$lastInsertId = $post->insert();
		if($lastInsertId) {
			$post->id = $lastInsertId;
			$saveMessage = "Post saved successfully!";
		}
	}
}
$postdetails = $post->getPost();
 
include('inc/header.php');
?>
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
				<div class="panel panel-default">
				  <div class="panel-heading">
					<h3 class="panel-title">Add New Post</h3>
				  </div>
				  <div class="panel-body">
				  
					<form method="post" id="postForm"  enctype="multipart/form-data">							
						<?php if ($saveMessage != '') { ?>
							<div id="login-alert" class="alert alert-success col-sm-12"><?php echo $saveMessage; ?></div>                            
						<?php } ?>
						<div class="form-group">
							<label for="title" class="control-label">Title</label>
							<input type="text" class="form-control" id="title" name="title" value="<?php echo $postdetails['title']; ?>" placeholder="Post title..">							
						</div>
						
						<div class="form-group">
							<label for="lastname" class="control-label">Abstract</label>							
							<textarea class="form-control" rows="5" id="message" name="message" placeholder="Post message.."><?php echo $postdetails['message']; ?></textarea>					
						</div>	
						
						<div class="form-group">
							<label for="capstonemembers" class="control-label">Capstone Members</label>
							<input type="text" class="form-control" id="capstonemembers" name="capstonemembers" value="<?php echo $postdetails['capstonemembers']; ?>" placeholder="Please use semicolon ';' to separate multiple individuals when there are two or more people.">							
						</div>


						<div class="form-group">
							<label for="capstone_advisor" class="control-label">Capstone Advisor</label>
							<input type="text" class="form-control" id="capstone_advisor" name="capstone_advisor" value="<?php echo $postdetails['capstone_advisor']; ?>" placeholder="Please input the name of the advisor...">							
						</div>

						<div class="form-group">
							<label for="capstone_mentor" class="control-label">Chairperson</label>
							<input type="text" class="form-control" id="capstone_mentor" name="capstone_mentor" value="<?php echo $postdetails['capstone_mentor']; ?>" placeholder="Please input the name of the Mentor...">							
						</div>

						<div class="form-group">
							<label for="panel_member" class="control-label">Defense Panel Member</label>
							<input type="text" class="form-control" id="panel_member" name="panel_member" value="<?php echo $postdetails['panel_member']; ?>" placeholder="Please use semicolon ';' to separate multiple individuals when there are two or more people.">							
						</div>


						
						<div class="form-group">
							<label for="sel1">Shool Year</label>
							<select class="form-control" id="category" name="category">
							<?php
							while ($category = $categories->fetch_assoc()) {
								$selected = '';
								if($category['name'] == $postdetails['name']) {
									$selected = 'selected="selected"';
								}									
								echo "<option value='".$category['id']."' $selected>".$category['name']."</option>";
							}
							?>							
							</select>
						</div>						
						<div class="form-group">
							<label for="status" class="control-label"></label>							
							<label class="radio-inline">
								<input type="radio" name="status" id="publish" value="published" <?php if($postdetails['status'] == 'published') { echo "checked"; } ?>>Publish
							</label>
							<label class="radio-inline">
								<input type="radio" name="status" id="draft" value="draft" <?php if($postdetails['status'] == 'draft') { echo "checked"; } ?>>Draft
							</label>
							<label class="radio-inline">
								<input type="radio" name="status" id="archived" value="archived" <?php if($postdetails['status'] == 'archived') { echo "checked"; } ?>>Archive
							</label>
							
							<br><br>
							<div class="form-group">
							 <label for="copyright" class="control-label">Copyright No.</label>
							 <input type="text" class="form-control" id="copyright" name="copyright" value="<?php echo $postdetails['copyright']; ?>" placeholder="Update The Copyright NO.">
							</div>	
							<div class="form-group">
							<?php if (!empty($postdetails['pdf_name']) && file_exists('pdf/' . trim($postdetails['pdf_name']))): ?>
    <p>Current PDF: 
        <a href="javascript:void(0);" onclick="openPdfInNewTab('pdf/<?php echo trim($postdetails['pdf_name']); ?>')">
            <?php echo trim($postdetails['pdf_name']); ?>
        </a>
    </p>

    <script>
        function openPdfInNewTab(pdfFilePath) {
            window.open(pdfFilePath, '_blank');
        }
    </script>
<?php endif; ?>

    <!-- Input for uploading a new PDF file -->
    <label for="pdf_file" class="control-label">Upload New PDF</label>
    <input type="file" name="pdf_file" id="pdf_file"> 

                            <!-- Checkbox to display PDF -->
							<input type="hidden" name="pdfdisplay" id="pdfdisplay" value="0">
<input type="checkbox" id="pdfdisplayCheckbox" name="pdfdisplay" value="1" <?php echo $postdetails['pdfdisplay'] == 1 ? 'checked' : ''; ?>>
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
