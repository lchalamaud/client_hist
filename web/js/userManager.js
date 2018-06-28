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

function switchUserRole( role ){
	var url = "/admin/manager/role/";

	$.ajax({
        type: "post",
        url: url,
        beforeSend: function(){

        },
        success: function(){
        	
        },
        error: function(){

        },
        data: {
        	'username': $('.selected .username').html(),
        	'role': role
        }
    });
}

$(document).ready( function () {

	disable('#delUser');

	$('#userList tbody tr').click( function() {
		if($(this).hasClass('selected')){
			$(this).removeClass('selected');
			disable('#delUser');
		}else{
			enable( '#delUser' )
			$('#userList').find('.selected').removeClass('selected');
			$(this).addClass('selected');
		}
	});

	$('.promoteUser').click( function() {
		var newRole;
		if( $('.selected .user').html() ){
			newRole = 'ROLE_ADMIN';
			switchUserRole(newRole);
		}else if( $('.selected .admin').html() ){
			newRole = 'ROLE_SUPER_ADMIN';
			switchUserRole(newRole);
		}
		
	});

	$('.demoteUser').click( function() {
		var newRole;
		if( $('.selected .super').html() ){
			newRole = 'ROLE_ADMIN';
			switchUserRole(newRole);
		}else if( $('.selected .admin').html() ){
			newRole = 'ROLE_USER';
			switchUserRole(newRole);
		}
	});
	
})
