<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: student_login.php");
    exit();
}
include_once 'inc/header3.php';

$article->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';

// Fetch the specific article based on the provided id
$singleArticle = $article->getSingleArticle(); // Implement this method in your Articles class

if ($singleArticle) {
   $bookmarkPostId = $singleArticle['bookmark_post_id'];
    $date = date_create($singleArticle['created']);
    $message = str_replace("\n\r", "<br><br>", $singleArticle['message']);  
    $capstonemembers = $singleArticle['capstonemembers'];
    $capstone_advisor = $singleArticle['capstone_advisor'];
    $capstone_mentor = $singleArticle['capstone_mentor']; 
    $panel_member = $singleArticle['panel_member'];
    $copyright = $singleArticle['copyright'];
    $pdf_name = $singleArticle['pdf_name'];
    $pdfdisplay = $singleArticle['pdfdisplay'];
?>
<div class="explore"> 
<div class="row">
  <div class="leftcolumn">
    <div class="card cardview">
    <a href="javascript:history.back()" class="backbtn">Back</a>
    <label class="ui-bookmark" style="float:right;">
    <input type="checkbox" class="bookmark-checkbox" data-post-id="<?php echo $singleArticle['id']; ?>"
        <?php echo $article->isPostBookmarked($_SESSION['user_id'], $singleArticle['id']) ? 'checked' : ''; ?>>
    <div class="bookmark">
        <svg viewBox="0 0 32 32">
            <g>
                <path
                    d="M27 4v27a1 1 0 0 1-1.625.781L16 24.281l-9.375 7.5A1 1 0 0 1 5 31V4a4 4 0 0 1 4-4h14a4 4 0 0 1 4 4z">
                </path>
            </g>
        </svg>
    </div>
</label>
      <h1><?php echo $singleArticle['title']; ?></h1> 
      <div class="centered-content titledes">
        <div class="rightdes">
          <h3>School Year: <span style="color: darkred;"><?php echo $singleArticle['category']; ?></span></h3>
        </div>
      </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.bookmark-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const postId = this.getAttribute('data-post-id');
                console.log('postId:', postId);

                // Send an AJAX request to bookmark_post.php
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'bookmark_post.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        try {
                            const response = JSON.parse(xhr.responseText);

                            // Handle the response (you can customize this part)
                            if (response.success) {
                                // Toggle the checkbox based on the bookmark status
                                checkbox.checked = response.isBookmarked;
                                // alert('Post ' + (response.isBookmarked ? 'bookmarked' : 'unbookmarked') + ' successfully!');
                            } else {
                                alert('Bookmarking failed. Please try again.');
                            }
                        } catch (error) {
                            console.error('Error parsing JSON response:', error);
                            alert('An error occurred. Please try again.');
                        }
                    }
                };

                // Prepare data and send the request
                const data = 'post_id=' + encodeURIComponent(postId);
                xhr.send(data);
            });
        });
    });
</script>
      <div class="">  
          <h3>Published on</h3>
          <?php echo date_format($date, "d F Y");	?>
        </div>
      <div class="capstonemembers">
          <br>
          <h3>Capstone Members</h3>
          <?php
            $capstonemembers = explode(";", $capstonemembers);
            foreach ($capstonemembers as $capsmember) {
                echo "<p> " . htmlspecialchars($capsmember) . "</p>";
            }
          ?>
        </div> 
        <div class="advisor">
  <h3>Advisor</h3>
  <?php if (!empty($capstone_advisor)): ?>
    <p><?php echo $capstone_advisor; ?></p>
  <?php else: ?>
    <p style="color: darkred;">- Not available</p>
  <?php endif; ?>
</div>

<div class="mentor">
  <h3>Chairperson</h3>
  <?php if (!empty($capstone_mentor)): ?>
    <p><?php echo $capstone_mentor; ?></p>
  <?php else: ?>
    <p style="color: darkred;">- Not available</p>
  <?php endif; ?>
</div>

        <div class="pannelmembers">
  <h3>Panel Members</h3>
  <?php
    if (!empty($panel_member)) {
      $panel_members = explode(";", $panel_member);
      foreach ($panel_members as $member) {
        echo "<p> " . htmlspecialchars($member) . "</p>";
      }
    } else {
      echo '<p style="color: darkred;">- Not available</p>';
    }
  ?>
</div>

      <h2>Abstract / Summary</h2>
      <p style="text-align: justify;"><?php echo $message; ?></p>
      <div class="People">
      

        <div class="copyright">
  <h3>Copyright No.</h3>
  <?php if (!empty($copyright)): ?>
    <p><?php echo $copyright; ?></p>
  <?php else: ?>
    <p style="color: darkred;">[n.d]</p>
  <?php endif; ?>
</div>

<br>
<p style="font-weight: bold;">Suggested Citation:</p>
        <div class="copy-box">
    <?php
    $capstonemembers = $singleArticle['capstonemembers'];

    // Extract capstone members
    $capstoneMembersArray = explode(";", $capstonemembers);

    // Count the number of capstone members
    $numCapstoneMembers = count($capstoneMembersArray);

    // Display the first name before ',' for the first capstone member
    if ($numCapstoneMembers > 0) {
        $firstWholeName = $capstoneMembersArray[0];
        $firstWord = explode(",", $firstWholeName)[0];
        echo htmlspecialchars($firstWord);
    }

    // Display 'et al.' if there are two or more capstone members
    if ($numCapstoneMembers >= 2) {
        echo " et al.";
    }

    // Format the creation date of the article
   // Replace creation date with category name in the citation
$categoryName = $singleArticle['category'];

// Check if the category name is not empty
if (!empty($categoryName)) {
    $citation = " (" . $categoryName . "). \"" . $singleArticle['title'] . "\"";

    // Display the citation inside the copy box
    echo $citation;
} else {
    // Handle the case where category name is empty
    echo "Category name not available for citation";
}
    ?>
    <div class="copy-button" onclick="copyToClipboard()"></div>
    <div class="check">Copied!</div>
</div>
<br>
<!-- End for pdf Display -->
<?php
if ($singleArticle) {
    // Display the PDF icon and provide a link to download the PDF if $pdfdisplay is not 0
    if (!empty($pdf_name) && $pdfdisplay != 0) {
        $pdfFileName = $pdf_name;
        $pdfFilePath = 'admin/pdf/' . $pdfFileName;

        // Add a link to download the corresponding PDF with the PDF icon
        if (file_exists($pdfFilePath)) {
            echo "<div class='pdf-icon-container'>";
            echo "<embed src='pdf_viewer.php?file=$pdfFilePath#toolbar=0' type='application/pdf' width='100%' height='600px'>";
            echo "</div>";
        } else {
            echo "<p>PDF is not available</p>";
        }
    } else {
        echo "<p style='color: red;'>PDF is not available for viewing</p>";
    }
}
?>




<!-- End for pdf Display -->
      </div>
    </div>
  </div>
  <?php include_once 'inc/rightcolomnforview.php'; ?> 
  <div class="end">
			<p>You Reach The End </p>
		</div>
</div>

<script>
  // Function to copy text to clipboard with animation
  function copyToClipboard() {
    var copyText = document.querySelector(".copy-box");
    var range = document.createRange();
    range.selectNode(copyText);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);
    document.execCommand("copy");
    window.getSelection().removeAllRanges();

    // Add the 'clicked' class for the animation
    copyText.classList.add('clicked');

    // Remove the 'clicked' class after a short delay
    setTimeout(function() {
      copyText.classList.remove('clicked');
    }, 300);

    // Optionally, you can handle additional feedback here

    // Show the "Copied!" message every time the button is clicked
    var checkMessage = document.querySelector(".check");
    checkMessage.style.opacity = 1;

    // Hide the "Copied!" message after a short delay
    setTimeout(function() {
      checkMessage.style.opacity = 0;
    }, 1000);
  }
</script>
<?php } else {
    // Handle the case where the specified article ID is not found 
  echo '<p style="text-align: center; font-size: xx-large;">Article not found;</p>';
}?>
<?php include_once 'inc/footer2.php'; ?>
 