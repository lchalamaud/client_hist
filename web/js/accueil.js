menuPos = $('#header').outerHeight();
$('.subMenu').offset({ top: menuPos, left: 0 });
$('.subMenu').css('width', 20+$('.txtBanner').outerWidth()+'px');
