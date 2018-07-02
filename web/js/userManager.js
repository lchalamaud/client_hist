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

        },
        success: function(){
        	$('.selected').remove();
        	disable('#delUser');
			disable('.promoteUser');
			disable('.demoteUser');
        	
        },
        error: function(){

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
