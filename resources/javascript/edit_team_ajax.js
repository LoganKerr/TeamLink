$(document).ready(function() {
	$("#delete-team").click(function() {
		var team_id = document.getElementById("team-id");
		var id = team_id.value;
		$.post("req_handler.php",
		{
			action: "delete_team",
			id: id
		},
		function(data, status) {
			window.location.reload();
		});
	});
});