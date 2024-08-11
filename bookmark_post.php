<?php
session_start();
include_once 'class/Articles.php';
include_once 'config/Database.php';

// Check if the request is a POST request and the user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $database = new Database();
    $conn = $database->getConnection();
    $articles = new Articles($conn);

    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];

    // Check if the post is already bookmarked by the user
    $isBookmarked = $articles->isPostBookmarked($user_id, $post_id);

    // If the post is already bookmarked, remove the bookmark; otherwise, add a bookmark
    if ($isBookmarked) {
        $result = $articles->removeBookmark($user_id, $post_id);
    } else {
        $result = $articles->bookmarkPost($user_id, $post_id);
    }

    // Return a JSON response
    echo json_encode(['success' => $result, 'isBookmarked' => !$isBookmarked]);
} else {
    // Handle unauthorized access or other cases
    // echo json_encode(['error' => 'Unauthorized']);
}
?>
