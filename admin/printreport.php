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
	$post->category_id = $_POST["category_id"];
	// $post->pdf_name = isset($_POST["pdf_name"]) ? $_POST["pdf_name"] : '';
}


$postdetails = $post->getPost();
 
include('inc/header.php');
?>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/posts.js"></script>	
<link href="css/style.css" rel="stylesheet" type="text/css">  
<link href="css/printreport.css" rel="stylesheet" type="text/css">  
<script>function printPage() { window.print();}</script>
<script>
    function printPage() {
        window.print();
    }
</script>

<style>
    @media print {
        .print-button {
            display: none;
        }
    }
</style>
</head>
<body>
<section id="main">
</div>
	<div class="container">
    <div class="logo">
    <img src="css/cicslogo.png" alt="Logo">
  </div>
  <div class="logo1">
    <img src="css/tradelogo.png" alt="Logo">
  </div>
		<div class="row">	
        <div class="state-university">
    <p>Republic of the Philippines</p>
    <h2>ZAMBOANGA PENINSULA POLYTECHNIC STATE UNIVERSITY</h2>
    <p>Region IX, Zamboanga Peninsula</p>
    <p>R.T. Lim Blvd., Zamboanga City</p>
    <p>Tel. No. (062) 993-0023</p>
    <div class="college">
    <h2>COLLEGE OF INFORMATION AND COMPUTING SCIENCE</h2>
    </div>
    </div>												
                    <table id="reportList" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Title</th>
								<th>Capstone Members</th>
                                <th>School Year</th>
								<th>Advisor</th>	
								<th>Chairperson</th>
                                <th>Panel Member</th>
								<th>Date of Published</th>																
							</tr>
                            <td><?php echo $postdetails['title']; ?></td>
                            <td><?php echo $postdetails['capstonemembers']; ?></td>
                            <td><?php echo $postdetails['name']; ?></td>
                            <td><?php echo $postdetails['capstone_advisor']; ?></td>
                            <td><?php echo $postdetails['capstone_mentor']; ?></td>
                            <td><?php echo $postdetails['panel_member']; ?></td>
                            <td><?php echo $postdetails['created']; ?></td> 
						</thead>
                        <div class="button-container print-buttons">
    <a href="report.php"><button type="button" class="btn btn-danger">Back</button></a>
    <a href="javascript:printPage()"><button type="button" class=" btn btn-primary">Print</button></a>
</div>

					</table>

                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <label for="approvalText">Approve by:</label>
                    <br>
                    <br>
<input type="text" id="approvalText" placeholder="" style="border: none; border-bottom: 1px solid #000; margin-right: 40px;">


<br>
<span style="text-decoration: none; margin-left:0px;">IT Capstone Professor/Research Coordinator</span>





		</div>
	</div>
</section>
<?php include('inc/footer.php'); ?>
