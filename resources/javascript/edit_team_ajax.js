$(document).ready(function() {
	// Will send ajax request on click of the delete team button
	$("#delete-team").click(function() {
		var id = $("#team-id").val();
		$.post("req_handler.php",
		{
			action: "delete_team",
			id: id
		},
		function(data, status) {
			window.location.reload();
		});
	});
	// Toggle the style of the team title on hover
	$("#team-title").hover(function() {
		$("#title-symbol").css("color", "#717b8c");
		$("#team-title").css("border", "3px solid #717b8c");
	},
	function() {
		$("#title-symbol").css("color", "rgba(113, 123, 140, 0.4)");
		$("#team-title").css("border", "3px solid white");
	});
	// Will replace the team title with a textbox to change the title
	$("#title-symbol").click(function() {
		$(".title-textbox").removeClass("hidden");
		$("#title-content").focus();
		$("#team-title").addClass("hidden");
	});
	// Performs ajax call to update the title of the team
	$("#title-confirm").click(function() {
		var title = $("#title-content").val();
        var id = $("#team-id").val();
		$.post("req_handler.php",
		{
			action: "change_title",
			title: title,
			id: id
		},
		function(data, status) {
			window.location.reload();
		});
	});
	// Will replace the title changing textbox with the original content
	$("#title-remove").click(function() {
		$(".title-textbox").addClass("hidden");
		$("#team-title").removeClass("hidden");
	});
	// Will replace the description text with a textarea to edit the description
	$("#desc-content").click(function() {
		$("#desc-textarea").removeClass("hidden");
		$("#desc-content").addClass("hidden");
		$("#desc-confirm").removeClass("hidden");
		$("#desc-remove").removeClass("hidden");
	});
	// Performs the ajax request to update the description of the team
	$("#desc-confirm").click(function() {
		var desc = $("#desc-textarea").val();
		var id = $("#team-id").val();
		$.post("req_handler.php",
		{
			action: "change_desc",
			desc: desc,
			id: id
		},
		function(data, status) {
			window.location.reload();
		});
	});
	$("#desc-remove").click(function() {
		$("#desc-textarea").addClass("hidden");
		$("#desc-content").removeClass("hidden");
		$("#desc-confirm").addClass("hidden");
		$("#desc-remove").addClass("hidden");
	});
});