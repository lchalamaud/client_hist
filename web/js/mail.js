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

	$('#onglet li').click(function(){
		$('#onglet').find('.active').removeClass('active');
		$(this).addClass('active');
		switch ($(this).html()){
			case 'Boîte de réception':
				$('.inbox').show();
				$('.affaire').hide();
				$('.other').hide();
				break;
			case 'Affaires':
				$('.inbox').hide();
				$('.affaire').show();
				$('.other').hide();
				break;
			case 'Autres':
				$('.inbox').hide();
				$('.affaire').hide();
				$('.other').show();
				break;
		}
	})
})