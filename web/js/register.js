$(document).ready(function () {
	$("#roleCommercial").click(function(){
		if($(this).is(":checked")){
			$(".formContent").append(
				"<div class='commercialForm' style='display: none;'>"+
					"<h2>Nom du Commercial</h2>"+
					"<input type='text'/>"+
					"<h2>Acronyme</h2>"+
					"<input type='text'/>"+
					"<h2>Couleur</h2>"+
					"<input type='number'/>"+
				"</div>"
			);
			$(".commercialForm").slideDown(function(){
				$(this).show();
			});
		}else{
			$(".commercialForm").slideUp(function(){
				$(this).remove();
			});
		}
	})
})