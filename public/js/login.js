var gUsername = '************';
var gPassword = '************';

$(document).ready(function() {
	$("#username").val(gUsername);
	$("#password").val(gPassword);

	$('#username').focus(function() {
		if ($(this).val() == gUsername) {
			$(this).val('');
		}
	});

	$('#password').focus(function() {
		if ($(this).val() == gUsername) {
			$(this).val('');
		}
	});
});