
<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: student_login.php");
    exit();
}
include_once 'inc/header3.php'; ?>
<div class="explore">
<div class="row">
  <h1>Capstone Projects of : 
  <?php
    if (isset($_GET['id'])) {
      $category_id = $_GET['id']; // Get the selected category_id from the URL

      if (is_numeric($category_id)) {
        $yearSectionName = $article->getYearSectionName($category_id); // Add a function to fetch the name

        if ($yearSectionName) {
          echo '<span style="color: Darkred;">' . $yearSectionName . '</span>';
        } else {
          echo ' <br> School Year not found';
        }
      } else {
        echo 'Invalid School Year selected';
      }
    } else {
      echo 'No School Year selected';
    } 
    ?>
  </h1>
  <div class="leftcolumn"> 
    <?php
    if (isset($_GET['id'])) {
      $category_id = $_GET['id']; // Get the selected category_id from the URL

      if (is_numeric($category_id)) {
        // Get articles based on the selected "School Year"
        $articles = $article->getArticlesByYearSection($category_id);

        if ($articles) {
          while ($post = $articles->fetch_assoc()) {
            $date = date_create($post['created']);
            $message = str_replace("\n\r", "<br><br>", $post['message']);
            $message = $article->formatMessage($message, 100);
            ?>
            <div class="card">
              <h2><a href="view2.php?id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></h2>
              <em><strong>Published on</strong>: <?php echo date_format($date, "d F Y"); ?></em>
              <em><strong>School Year</strong> <a href="#" target="_blank"><?php echo $post['category']; ?></a></em>
              <br><br>
              <article>
                <p><?php echo $message; ?></p>
              </article>
              <a class="btn btn-blog pull-right" href="view2.php?id=<?php echo $post['id']; ?>">READ MORE</a>
            </div>
            <?php
          }
        } else {
          echo '<div class="card end ">No articles found for this School Year.</div>';
        }
      } else {
        echo '<div class="card end ">Invalid School Year selected.</div>';
      }
    } else {
      echo ' <div class="card end ">No School Year selected.</div>';
    }
    ?>
    <div class="end">
			<p>You Reach The End </p> 
		</div>
  </div>
  
  <?php include_once 'inc/rightcolomn.php'; ?>
</div>

<?php include_once 'inc/footer2.php'; ?>
