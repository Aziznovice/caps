<!-- <script>
  // Scroll down 25% of the screen height when the page is loaded
  window.onload = function () {
    var screenHeight = window.innerHeight;
    var scrollPosition = screenHeight * 0.90; // Adjust the percentage as needed
    window.scrollTo({ top: scrollPosition, behavior: 'smooth' });
  };
</script> -->
<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: student_login.php");
    exit();
}
include_once 'inc/header3.php';

// Initialize the search query
$searchQuery = '';

if (isset($_GET['search'])) {
  $searchQuery = $_GET['search'];
}

?>
<div class="explore">
<div class="row">
  <div class="leftcolumn">
    
  <h1>Search: <span style="color: Darkred;"><?php echo htmlspecialchars($searchQuery); ?></span></h1>

    <?php
    // Continue with the rest of your code
    if (!empty($searchQuery)) {
        $searchResults = $article->searchArticles($searchQuery);

        if ($searchResults->num_rows > 0) {
          while ($articleData = $searchResults->fetch_assoc()) {
            echo '<div class="card cardview">';
            echo '<h2><a href="view2.php?id=' . $articleData['id'] . '">' . $articleData['title'] . '</a></h2>';
            echo '<em><p><strong>Publish Date:</strong> ' . $articleData['created'] . '</p></em>';
            echo '<em><h5><strong>School Year:</strong> <a href="YearAndsections.php?id=' . $articleData['category_id'] . '">' . $article->getYearSectionName($articleData['category_id']) . '</a></h5></em>';
            echo '<p>' . $article->formatMessage($articleData['message'], 20) . '</p>';
            echo '<a class="btn btn-blog pull-right" href="view2.php?id=' . $articleData['id'] . '">READ MORE</a>';
            echo '</div>';
          }
        } else {
            echo '<p class="end">No results found.</p>'; 
        }
    }
    ?>
  </div>

  <!-- The right column remains the same -->
  <?php include_once 'inc/rightcolomn.php'; ?>
  <div class="end">
			<p>You Reach The End </p>
		</div>
</div>
 
<?php include_once 'inc/footer2.php';
