<?php
// Function to check if the current page matches the link
function isPageActive($page) {
    $currentPage = basename($_SERVER['PHP_SELF']);
    return ($currentPage == $page) ? 'active' : '';
}
?> 
 
<div class="col-md-3">
    <div class="list-group">
        <a href="dashboard.php" class="list-group-item <?php echo isPageActive('dashboard.php'); ?>"> 
            <span class="pad glyphicon glyphicon-cog" aria-hidden="true"></span>
            Dashboard <span class="badge"><?php echo $post->totalPost() + $category->totalCategory() + $user->totalUser() + $student->totalstudent(); ?></span>
        </a>
        <a href="posts.php" class="list-group-item <?php echo isPageActive('posts.php'); ?>">
            <span class="pad glyphicon glyphicon-pencil" aria-hidden="true"></span> Archive <span class="badge"><?php echo $post->totalPost(); ?></span>
        </a>
        <?php  
        // Check user type
        if ($_SESSION["user_type"] == 1) {
            // Display the Users link only if the user type is 1 (Administrator)
            echo '<a href="categories.php" class="list-group-item '.isPageActive('categories.php').'"><span class="pad glyphicon glyphicon-folder-open" aria-hidden="true"></span> School Year <span class="badge">' . $category->totalCategory() . '</span></a>';
        }
        ?>
 
        <?php
        // Check user type
        if ($_SESSION["user_type"] == 1) {
            // Display the Users link only if the user type is 1 (Administrator)
            echo '<a href="users.php" class="list-group-item '.isPageActive('users.php').'"><span class="pad glyphicon glyphicon-user" aria-hidden="true"></span> Users <span class="badge">' . $user->totalUser() . '</span></a>';
        }
        ?>
 <?php if ($_SESSION["user_type"] == 1): ?>
    <a href="students_acc.php" class="list-group-item <?php echo isPageActive('students_acc.php'); ?>">
        <span class="pad glyphicon glyphicon-list-alt" aria-hidden="true"></span> Students
        <span class="badge"><?php echo  $student->totalstudent(); ?></span>
        <span id="newStudentCount" class="badge">+0</span> 
    </a>
    <script>
        $(document).ready(function () {
            function updateStudentCount() {
                $.ajax({
                    url: 'get_student_count.php',
                    dataType: 'json',
                    success: function (data) {
                        // Add a '+' before the number if it's not 0
                        const countText = data.count !== 0 ? '+' + data.count : '0';

                        // Set badge text
                        $('#newStudentCount').text(countText);

                        // Change badge background color to red if the count is not 0
                        if (data.count !== 0) {
                            $('#newStudentCount').css('background-color', 'red');
                        } else {
                            // Reset to default background color if count is 0
                            $('#newStudentCount').css('background-color', '');
                        }
                    }
                });
            }

            // Initial call
            updateStudentCount();

            // Update every 5 seconds (adjust as needed)
            setInterval(updateStudentCount, 5000);
        });
    </script>
<?php endif; ?>





        <a href="report.php" class="list-group-item <?php echo isPageActive('report.php'); ?>">
            <span class="pad glyphicon glyphicon-file" aria-hidden="true"></span> Report <span class="badge"><?php echo $post->totalPost(); ?></span>
        </a>
    </div>
</div> 
