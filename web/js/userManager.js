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

function switchUserRole( role, promote ){
	var url = "/admin/manager/role/";

	$.ajax({
        type: "post",
        url: url,
        beforeSend: function(){

        },
        success: function(){
        	$('.selected .'+(role.split('_')[1]).toLowerCase()).append("&times;");
        	if( promote ){
        		$('.selected .'+(role.split('_')[1]).toLowerCase()).next().empty();
        	}else{
        		$('.selected .'+(role.split('_')[1]).toLowerCase()).prev().empty();
        	}
        	
        },
        error: function(){

        },
        data: {
        	'username': $('.selected .username').html(),
        	'role': role
        }
    });
}
function delUser(){
	var url ="/admin/manager/del/";
	$.ajax({
        type: "post",
        url: url,
        beforeSend: function(){
        	$('#delConfirmBtn').prop("disabled",true);
            $('html').css( 'cursor' , 'wait');
            $('#delResponse').empty().append('<p style="color: grey;margin-left:10px;">Suppression en cours...</p>');
        },
        success: function(){
        	$('html').css( 'cursor' , 'default');
        	$('.modal').hide();
        	$('#delConfirmBtn').prop("disabled",false);
        	$('#delResponse').empty()
        	$('.selected').remove();
        	disable('#delUser');
			disable('.promoteUser');
			disable('.demoteUser');
        	
        },
        error: function(){
        	$('#delResponse').empty().append('<p style="color: red;margin-left:10px;">Erreur dans la suppression de l\'utilisateur.</p>');
            $('html').css( 'cursor' , 'default');
            $('#delConfirmBtn').prop("disabled",false);
        },
        data: {
        	'username': $('.selected .username').html(),
        }
    });
}


$(document).ready( function () {

	disable('#delUser');
	disable('.promoteUser');
	disable('.demoteUser');

	$('#userList tbody tr').click( function() {
		if($(this).hasClass('selected')){
			$(this).removeClass('selected');
			disable('#delUser');
			disable('.promoteUser');
			disable('.demoteUser');
		}else{
			enable( '#delUser' );
			enable('.promoteUser');
			enable('.demoteUser');
			$('#userList').find('.selected').removeClass('selected');
			$(this).addClass('selected');
		}
	});

	$('#delUser').click(function(){
		$('#delModalNom').empty().append($('.selected').find('.username').html())
		$('.modal').show();
	})

	window.onclick = function(event) {
		console.log(event.target)
	    if ($(event.target).hasClass('modal')) {
	        $('.modal').hide();
	    }
	}
	$('.close').on('click', function() {
		$('.modal').hide();
	});

	$('#delConfirmBtn').click(function(){
		delUser();
	})

	$('.promoteUser').click( function() {
		var newRole;
		if( $('.selected .user').html() ){
			newRole = 'ROLE_ADMIN';
			switchUserRole(newRole, 1);
		}else if( $('.selected .admin').html() ){
			newRole = 'ROLE_SUPER_ADMIN';
			switchUserRole(newRole, 1);
		}
		
	});

	$('.demoteUser').click( function() {
		var newRole;
		if( $('.selected .super').html() ){
			newRole = 'ROLE_ADMIN';
			switchUserRole(newRole, 0);
		}else if( $('.selected .admin').html() ){
			newRole = 'ROLE_USER';
			switchUserRole(newRole, 0);
		}
	});
	
})
