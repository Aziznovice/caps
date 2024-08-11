$(document).ready(function(){
	var postsData = $('#postsList').DataTable({
		"lengthChange": true, // enable length change
		"lengthMenu": [10, 25, 50, 100], // set length menu options
		"processing":true,
		"serverSide":true,
		"order":[],
		"searching": true, 
		"paging": true,
		"ordering":true,
		"ajax":{
			url:"manage_posts.php", 
			type:"POST",
			data:{action:'postListing'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 4, 3, 6, 5, 7, 8],  // Columns 0 (PDF), 7 (Delete), and 8 (Edit) are unorderable
                "orderable": false,
			},
		],
		"pageLength": 10
	});		
	$(document).on('click', '.delete', function(){
		var postId = $(this).attr("id");		
		var action = "postDelete";
		if(confirm("Are you sure you want to delete this post?")) {
			$.ajax({
				url:"manage_posts.php",
				method:"POST",
				data:{postId:postId, action:action},
				success:function(data) {					
					postsData.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	
});
