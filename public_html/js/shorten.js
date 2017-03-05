$(document).ready(function(){
	$('#shortenForm').on('submit', function(e){
		e.preventDefault();

		$.ajax ({
			url: "/short",
			type: "POST",
			data: $(this).serialize(),
			success: function(data) {
				console.log(data);
			},
			error: function(jxhr, status, error) {
				console.log(status);
			}
		});

	});
});