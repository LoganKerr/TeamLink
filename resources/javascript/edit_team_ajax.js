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
		$("#title-symbol").show();
		$("#team-title").css("border", "3px solid red");
	});
	$("#team-title").mouseleave(function() {
		$("#title-symbol").hide();
		$("#team-title").css("border", "none");
	});
	$("#team-title").click(function() {
		$(".title-textbox").show();
		$("#team-title").hide();
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
		$(".title-textbox").hide();
		$("#team-title").show();
	});
	$("#desc-content").click(function() {
		$("#desc-textarea").show();
		$("#desc-content").hide();
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
		$("#desc-textarea").hide();
		$("#desc-content").show();
		$("#desc-confirm").addClass("hidden");
		$("#desc-remove").addClass("hidden");
	});
});