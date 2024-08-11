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
<link href="css/style.css" rel="stylesheet" type="text/css" >  
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
  <div class="panel-heading" style="background-color: maroon;">
    <h3 class="panel-title" style="color:white;" >Website Overview</h3>
  </div>
  <div class="panel-body">
   <div class="col-md-3">
     <div class="well dash-box" style="padding: 21px; height: 17rem;">
       <h2><span class="pad glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $user->totalUser(); ?></h2>
       <h4>Users</h4>
     </div>
   </div>
   <div class="col-md-3">
     <div class="well dash-box " style="padding: 21px; height: 17rem;">
       <h2><span class="pad glyphicon  glyphicon-folder-open" aria-hidden="true"></span> <?php echo $category->totalCategory(); ?></h2>
       <h4>School Year</h4>
     </div> 
   </div>
   <div class="col-md-3">
     <div class="well dash-box" style="padding: 21px; height: 17rem;">
       <h2><span class="pad glyphicon glyphicon-pencil" aria-hidden="true"></span><?php echo $post->totalPost(); ?></h2>
       <h4>Posts</h4>
     </div>
   </div>   
   <div class="col-md-3">
     <div class="well dash-box" style="padding: 21px; height: 17rem;">
       <h2><span class="pad glyphicon glyphicon-list-alt" aria-hidden="true"></span><?php echo $student->totalstudent(); ?></h2>
       <h4>Students</h4>
     </div>
   </div>
  </div>
</div>


      </div>
    </div>
  </div>
</section>


 <?php include('inc/footer.php');?>
