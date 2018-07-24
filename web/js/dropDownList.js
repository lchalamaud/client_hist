menuPos = $('.txtBanner').position().top+$('.txtBanner').outerHeight();
$('.subMenu').offset({ top: menuPos, left: 0 });
$('.subMenu').css('width', 20+$('.txtBanner').outerWidth()+'px');

$(document).ready(function(){

	$(document).click(function(e){
		if(!$(e.target).parents().hasClass("dropdown")){
			$('.listcontent').hide();
		}
	})
	$('.client_Tab tbody').on('click', '.dropdown',function(){
		$(this).find('ul').slideToggle("fast");
	})
	
	$('.client_Tab tbody').on('click', '.listcontent li', function(){
		$(this).closest('dd').prev().html($(this).html());
	})
})