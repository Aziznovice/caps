$(document).ready(function(){
	var usersData = $('#studentList').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,
		"searching": true, 
		"ordering": false,
		"order":[],
		"ajax":{
			url:"manage_student.php",
			type:"POST",
			data:{action:'studentListing'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 4, 5],
				"orderable":false,
			},
		],
		"pageLength": 10
	});		
	$(document).on('click', '.delete', function(){
		var userId = $(this).attr("id");		
		var action = "studentDelete";
		if(confirm("Are you sure you want to delete this user?")) {
			$.ajax({
				url:"manage_student.php",
				method:"POST",
				data:{userId:userId, action:action},
				success:function(data) {					
					usersData.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	
});