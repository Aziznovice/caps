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
include('inc/header.php'); 
?>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/printreport.js"> </script>
<link href="css/style.css" rel="stylesheet" type="text/css" >  
<link href="css/wholeprintreport.css" rel="stylesheet" type="text/css">  
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
    <div class="button-container print-buttons">
                             <a href="report.php"><button type="button" class="btn btn-danger">Back</button></a>
                             <a href="javascript:printPage()"><button type="button" class="btn btn-primary">Print</button></a>
                        </div>
	               <div class="container2">
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
						</thead>
					</table>
					</div>
					<br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <label for="approvalText">Approve by:</label>
                    <br>
                    <br>
<div class="approve">
<input type="text" id="approvalText" placeholder="" style="border: none; text-align: center; font-size:large; font-family: sans-serif;">
<br>
<span style="text-decoration: none; margin-left:0px; border-top:solid;">IT Capstone Professor/Research Coordinator</span>
		</div>    
</div>
	</div>
</section>
<?php include('inc/footer.php');?>
