<?php  
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: student_login.php");
    exit();
}

?>
<?php include_once 'inc/header3.php'; ?>
<!-- <script>
  // Scroll down 25% of the screen height when the page is loaded
  window.onload = function () {
    var screenHeight = window.innerHeight;
    var scrollPosition = screenHeight * .90; // Adjust the percentage as needed
    window.scrollTo({ top: scrollPosition, behavior: 'smooth' });
  };
</script> -->
<div class="explore">
<div class="row">
  <div class="leftcolumn">
    <div class="card capstone-list">
      <?php
      // Get sections and titles from the database
      $sections = $article->getYearSectionData();

      foreach ($sections as $section) {
        echo '<div class="section">';
        echo '<h2><a href="YearAndsections.php?id=' . $section['id'] . '">' . htmlspecialchars($section['name']) . '</a></h2>';
        echo '<ul>';

        // Get titles for the current section
        $sectionTitles = $article->getArticlesByYearSection($section['id']);

        while ($title = $sectionTitles->fetch_assoc()) {
          echo '<li><a href="view2.php?id=' . $title['id'] . '">' . htmlspecialchars($title['title']) . '</a></li>';
        }

        echo '</ul>';
        echo '</div>';
      }
      ?>

    </div>
    <div class="end">
			<p>You Reach The End </p>
		</div>
  </div>
  <?php include_once 'inc/rightcolomn.php'; ?>
  
</div>

<?php include_once 'inc/footer2.php'; ?> 
