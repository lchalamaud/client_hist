function disable( button ){
        $(button).prop("disabled",true);
        $(button).removeClass('enable');
        $(button).addClass('disable');
}
function enable( button ){
        $(button).prop("disabled",false);
        $(button).removeClass('disable');
        $(button).addClass('enable');
}

function delComm( ){
	var acronyme = { 'acronyme' : $(".selected .acronyme").html() }

	var url = "/commercial/del/";
	$.ajax({
        type: "post",
        url: url,
        beforeSend: function(){
        	$('#delConfirmBtn').prop("disabled",true);
        	$('html').css( 'cursor' , 'wait');
        	$('#delResponse').empty().append('<p style="color: grey;margin-left:10px;">Suppression en cours...</p>');
        },
        success: function(data){
        	if( data.rsp == 1 ){
        		$(".selected").remove();
        		$("#delModal").css('display', 'none');
        	}else{
        		$('#delResponse').empty().append(
        			'<p style="color: red;margin-left:10px;">Il reste des affaires associées à ce commercial.<br/>Voulez-vous vraiment supprimer toutes ces affaires?</p>'+
        			'<input id="delAffaireByTacheConfirm" type="button" class="button" value="Oui">'+
        			'<input type="button" class="button close" value="Non">'
        			);
        		$('.deleteBtn').hide();
	            $('#delConfirmBtn').prop("disabled",false);
        	}
            $('html').css( 'cursor' , 'default');
        },
        error: function(){
        	$('#delResponse').empty().append('<p style="color: red;margin-left:10px;">Erreur dans la suppression du commercial.</p>');
            $('html').css( 'cursor' , 'default');
            $('#delConfirmBtn').prop("disabled",false);
        },
        data: acronyme
    });
}

function delCommForce( ){
	var acronyme = { 'acronyme' : $(".selected .acronyme").html() }

	var url = "/commercial/del/force";
	$.ajax({
        type: "post",
        url: url,
        beforeSend: function(){
        	$('#delConfirmBtn').prop("disabled",true);
        	$('html').css( 'cursor' , 'wait');
        	$('#delResponse').empty().append('<p style="color: grey;margin-left:10px;">Suppression en cours...</p>');
        },
        success: function(data){
    		$(".selected").remove();
    		$("#delModal").css('display', 'none');
            $('html').css( 'cursor' , 'default');
            $('#delConfirmBtn').prop("disabled",false);
        },
        error: function(){
        	$('#delResponse').empty().append('<p style="color: red;margin-left:10px;">Erreur dans la suppression du commercial.</p>');
            $('html').css( 'cursor' , 'default');
            $('#delConfirmBtn').prop("disabled",false);
        },
        data: acronyme
    });
}

$(document).ready( function () {

	disable('#delComm');
    disable('#modifComm');

	$('#commTab tbody tr').click( function() {
		if($(this).hasClass('selected')){
			$(this).removeClass('selected');
			disable('#delComm');
            disable('#modifComm');
		}else{
			enable('#delComm');
            enable('#modifComm');
			$('#commTab').find('.selected').removeClass('selected');
			$(this).addClass('selected');
            var commercial = $(this).find('.acronyme').html();
            $('.modifComm').attr('href', '/commercial/modif/'+commercial+'/');
            console.log(commercial)
		}
	})

	$('#delConfirmBtn').click(function(){
		delComm( );
	})

	$('#delModal').on('click', '#delAffaireByTacheConfirm', function(){
		delCommForce( );
	})

})

$('#delModal').on('click', '.close', function() {
    $('#delModal').css('display', 'none');
    $('#delModalNom').empty();
    $('#delResponse').empty();

});

window.onclick = function(event) {
	if (event.target.id == 'delModal'){
		$('#delModal').css('display', 'none');
		$('#delModalNom').empty();
		$('#delResponse').empty();
	}
	
}

$('#delComm').click( function() {
	$('.deleteBtn').show();
	var nom = $(".selected .nom").html();
	$('#delModalNom').append(nom);
	$('#delModal').css('display', 'block');
});