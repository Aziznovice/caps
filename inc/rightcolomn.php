<div class="rightcolumn">
  <div class="card">
      <h3>Search:</h3>
      <form action="<?php echo SITEURL; ?>SearchResult.php" class="search-wrapper cf" method="get">
        <input type="text" id="search" name="search" placeholder="Search here..." required="">
        <input class="#" type="hidden" value="Search">
        <button type="submit" id="search">Search</button>
    </form>


    </div>
    <div class="rightcard">
    <h3>School Year</h3>
    <ul class="fakeimg">
        <?php
        $yearsectionResult = $article->getYearSectionData();
        foreach ($yearsectionResult as $yearsection) {
            // Loop through the database results and display each title as a list item
            echo '<li><a href="YearAndsections.php?id=' . $yearsection['id'] . '">' . $yearsection['name'] . '</a></li>';
        }
        ?>
    </ul>
</div>

    <div class="rightcard">
      <h3>Recently Added</h3>
      <ul class="capsfakeimg">
        <?php
        $titlesResult = $article->getRecentArticleTitles();
        while ($title = $titlesResult->fetch_assoc()) {
            // Loop through the database results and display each title as a list item
            echo '<li><a href="view2.php?id=' . $title['id'] . '">' . $title['title'] . '</a></li>';
        }
        ?>
    </ul>
     
    </div>
    <a href="https://zppsu.edu.ph/" class="lastrightcard"> 
    <!-- <h3>Follow Me</h3>
    <img src="./img/ZPPSUlink.png" alt="ZPPSU">
    <p>Some text..</p> -->
</a>

  </div>
  </div>