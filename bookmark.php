<?php
session_start();
include_once 'config/Database.php';
include_once 'class/Articles.php';

if (isset($_SESSION['user_id'])) {
    $database = new Database();
    $conn = $database->getConnection();
    $articles = new Articles($conn);
    $user_id = $_SESSION['user_id'];

    // Get bookmarked posts for the user
    $bookmarkedPosts = $articles->getBookmarkedPosts($user_id);
} else {
    // Handle unauthorized access or other cases
    echo "Unauthorized";
    exit(); // Add an exit to stop executing the rest of the code
}

include_once 'inc/header3.php';
?>
<div class="explore">
    <div class="row">
        <div class="leftcolumn">
            <h1>Your Bookmarks</h1>

            <?php
            if ($bookmarkedPosts->num_rows > 0) {
                while ($bookmark = $bookmarkedPosts->fetch_assoc()) {
                    // Get details of the bookmarked article
                    $articleDetails = $articles->getSingleArticleById($bookmark['post_id']);

                    if ($articleDetails) {
                        echo '<div class="card cardview">';
                        echo '<h2><a href="view2.php?id=' . $articleDetails['id'] . '">' . $articleDetails['title'] . '</a></h2>';
                        echo '<em><p><strong>Publish Date:</strong> ' . $articleDetails['created'] . '</p></em>';
                        echo '<em><h5><strong>School Year:</strong> <a href="YearAndsections.php?id=' . $articleDetails['category_id'] . '">' . $articles->getYearSectionName($articleDetails['category_id']) . '</a></h5></em>';
                        echo '<p>' . $articles->formatMessage($articleDetails['message'], 20) . '</p>';
                        echo '<a class="btn btn-blog pull-right" href="view2.php?id=' . $articleDetails['id'] . '">READ MORE</a>';
                        echo '</div>';
                    }
                }
            } else {
                echo '<p class="end">You don\'t have any bookmarked capstones. Explore and bookmark capstones to view them here.</p>';
            }
            ?>
        </div>

        <!-- The right column remains the same -->
        <?php include_once 'inc/rightcolomn.php'; ?>
        <div class="end">
            <p>You Reach The End </p>
        </div>
    </div>
</div>

<?php include_once 'inc/footer2.php'; ?>
