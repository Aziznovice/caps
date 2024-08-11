<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: student_login.php");
    exit();
}


include_once 'inc/header2.php';
?>
<script>
    // Store the scroll position in session storage when a post link is clicked
    $(".card a").click(function() {
        sessionStorage.setItem('scrollPosition', $(window).scrollTop());
    });

    // Restore the scroll position when returning from the 'view.php' page
    $(document).ready(function() {
        var storedScrollPosition = sessionStorage.getItem('scrollPosition');
        if (storedScrollPosition !== null) {
            $(window).scrollTop(storedScrollPosition);
            sessionStorage.removeItem('scrollPosition');
        }
    });
</script>
<div class="explore" id="explorecaps">
<script>
    $(document).ready(function() {
      $(".scroll-container").click(function(event) {
        event.preventDefault();
        $('html, body').animate({
          scrollTop: $("#explorecaps").offset().top
        }, 1000); // Adjust the duration (1000ms = 1 second) as needed
      });
    });
  </script>
<div class="row"> 
  <div class="leftcolumn" id="left">
  <div class="sort-dropdown">
                <label for="sort">Sort By:</label>
                <select id="sort" onchange="sortPosts()">
                    <option value="recent" <?php if (!isset($_GET['sort']) || $_GET['sort'] === 'recent') echo 'selected'; ?>>
                        Recent
                    </option>
                    <option value="oldest" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'oldest') echo 'selected'; ?>>
                        Oldest
                    </option>
                </select>
            </div>
<?php
// Get the sorting option from the URL
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'recent';

// Get the page number from the URL, default to 1 if not set
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;
// Get the sorting option from the URL
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'recent';

// Adjust the SQL query based on the sorting option
// Adjust the SQL query based on the sorting option and pagination
if ($sortOption == 'oldest') {
    $result = $article->getArticles('ASC', $perPage, $offset);
} else {
    $result = $article->getArticles('DESC', $perPage, $offset); 
}

while ($post = $result->fetch_assoc()) {
    $date = date_create($post['created']);
    $message = str_replace("\n\r", "<br><br>", $post['message']);
    $message = $article->formatMessage($message, 100);
    $category_id = $post['category_id'];
    ?>
    <div class="card">
        <h2><a href="view2.php?id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></h2>
        <em><strong>Published on</strong>: <?php echo date_format($date, "d F Y"); ?></em>
        <em><strong>School Year</strong> <a href="YearAndsections.php?id=<?php echo $category_id; ?>"
                                               target="_blank"><?php echo $post['category']; ?></a></em>

        <br><br>
        <article>
            <p><?php echo $message; ?> </p>
        </article>
        <a class="btn btn-blog pull-right" href="view2.php?id=<?php echo $post['id']; ?>">READ MORE</a>
    </div>
<?php } ?> 
<?php
$totalPages = ceil($article->totalPost() / $perPage);
?>

<div class="pagination">
<div class="page-label" style="margin-right: 2vh;color: darkred;">Pages:</div>
    <?php if ($page > 1) : ?>
        <a href="?page=<?php echo $page - 1; ?>&sort=<?php echo $sortOption; ?>" class="page-link">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <a href="?page=<?php echo $i; ?>&sort=<?php echo $sortOption; ?>" class="page-link <?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($article->hasMorePages($perPage)) : ?>
        <a href="?page=<?php echo $page + 1; ?>&sort=<?php echo $sortOption; ?>" class="page-link">Next</a>
    <?php endif; ?>
</div>
<script>
// Function to handle pagination link click
document.addEventListener('DOMContentLoaded', function() {
    // Attach the click event to elements with class 'my-pagination-link'
    document.querySelectorAll('.my-pagination-link').forEach(function(link) {
        link.addEventListener('click', handlePaginationClick);
    });

    // Additional code if needed
});

// function handlePaginationClick(e) {
//     e.preventDefault();

//     var targetHref = this.getAttribute('href');
//     var urlWithoutParams = targetHref.split('?')[0];
//     var screenHeight = window.innerHeight;
//     var offset = screenHeight * 0.25;
// }
// </script>


		<div class="end">
			<p>You Reach The End </p> 
		</div>
  </div>
  <?php include_once 'inc/rightcolomn.php'; ?>
</div>
<script>
    function sortPosts() {
        var selectedOption = $('#sort').val();

        $.ajax({
            type: 'GET',
            url: 'index.php',
            data: { sort: selectedOption },
            success: function (data) {
                // Update the content of the container with new data
                $('.leftcolumn').html($(data).find('.leftcolumn').html());
            }
        });
    }
</script>



<?php include_once 'inc/footer2.php'; ?>  