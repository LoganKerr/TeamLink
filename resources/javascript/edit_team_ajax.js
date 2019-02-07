$(document).ready(function() {
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
	$("#team-title").mouseenter(function() {
		$("#title-symbol").removeClass("hidden");
		$("#team-title").css("border", "3px solid #717b8c");
	});
	$("#team-title").mouseleave(function() {
		$("#title-symbol").addClass("hidden");
		$("#team-title").css("border", "3px solid white");
	});
	$("#team-title").click(function() {
		$(".title-textbox").removeClass("hidden");
		$("#team-title").addClass("hidden");
	});
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
	$("#title-remove").click(function() {
		$(".title-textbox").addClass("hidden");
		$("#team-title").removeClass("hidden");
	});
	$("#desc-content").click(function() {
		$("#desc-textarea").removeClass("hidden");
		$("#desc-content").addClass("hidden");
		$("#desc-confirm").removeClass("hidden");
		$("#desc-remove").removeClass("hidden");
	});
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