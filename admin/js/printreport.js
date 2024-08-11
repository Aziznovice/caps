// script.js

$(document).ready(function () {
    var postsData = $('#reportList').DataTable({
      "lengthChange": false,
      "lengthMenu": false,
      "processing": true,
      "serverSide": true,
      "order": [],
      "searching": true,
      "paging": false,
      "ordering": false,
      "ajax": {
        url: "manage_report.php",
        type: "POST", 
        data: { action: 'reportListing' },
        dataType: "json"
      },
      "pageLength": 10
    });
  
    // Add DataTable search functionality
    $('#Searchinput').on('keyup', function () {
      postsData.search($(this).val()).draw();
    });
  
    $(document).on('click', '.delete', function () {
      var postId = $(this).attr("id");
      var action = "postDelete";
      if (confirm("Are you sure you want to delete this post?")) {
        $.ajax({
          url: "manage_report.php",
          method: "POST",
          data: { postId: postId, action: action },
          success: function (data) {
            postsData.ajax.reload();
          }
        })
      } else {
        return false;
      }
    });
  });
  