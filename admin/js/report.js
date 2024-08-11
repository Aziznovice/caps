$(document).ready(function(){
	var postsData = $('#reportList').DataTable({
		"lengthChange": true, // enable length change
		"lengthMenu": [10, 25, 50, 100], // set length menu options
		"processing":true,
		"serverSide":true,
		"order":[],
		"searching": true, 
		"paging": true,
		"ordering":false,
		"ajax":{
			url:"manage_report.php",
			type:"POST", 
			data:{action:'reportListing'},
			dataType:"json"  
		},
		"pageLength": 10
	});		
	$(document).on('click', '.delete', function(){
		var postId = $(this).attr("id");		
		var action = "postDelete";
		if(confirm("Are you sure you want to delete this post?")) {
			$.ajax({
				url:"manage_report.php",
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
