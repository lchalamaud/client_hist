$(document).ready(function(){
	$('.header').click(function(){
		message = $(this).parents(".message");
		body = $(this).next();
		if( message.hasClass('shown')){
			message.removeClass('shown');
			body.slideUp()
		}else{
			message.addClass('shown');
			body.slideDown("slow");

		}
	})
})