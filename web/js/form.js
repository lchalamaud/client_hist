$(document).ready(function(){
	$('input').keyup(function(){
		console.log($(this).val())
		if(!$(this).val()){
			$(this).css('background-color', '#e5e7ea')
		}else{
			$(this).css('background-color', 'white')
		}
	})
})